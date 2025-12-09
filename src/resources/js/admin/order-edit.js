function formatPrice(price) {
    return new Intl.NumberFormat('ru-RU').format(price);
}

function updateSelectedTickets() {
    const checked = document.querySelectorAll('.ticket-checkbox:checked');
    const countSpan = document.getElementById('selected-count');
    const chipsContainer = document.getElementById('selected-tickets');

    let ticketsTotal = 0;
    chipsContainer.innerHTML = '';

    checked.forEach(cb => {
        const price = parseFloat(cb.dataset.price) || 0;
        ticketsTotal += price;

        const chip = document.createElement('div');
        chip.className = 'inline-flex items-center bg-indigo-100 text-indigo-800 rounded-full px-4 py-1.5 text-sm font-medium';
        chip.innerHTML = `
            <span>${cb.dataset.text}</span>
            <button type="button" class="ml-3 text-indigo-600 hover:text-indigo-900">×</button>
        `;
        chip.querySelector('button').onclick = () => {
            cb.checked = false;
            updateSelectedTickets();
            updateTotalPrice();
        };
        chipsContainer.appendChild(chip);
    });

    if (countSpan) countSpan.textContent = checked.length;
    return ticketsTotal;
}

function updateTotalPrice() {
    let total = 0;

    // Текущие билеты
    document.querySelectorAll('.current-ticket-item').forEach(item => {
        const price = parseFloat(item.dataset.price || 0);
        total += price;
    });

    // Новые выбранные билеты
    document.querySelectorAll('.ticket-checkbox:checked').forEach(cb => {
        total += parseFloat(cb.dataset.price || 0);
    });

    // Развлечения
    document.querySelectorAll('.entertainment-item').forEach(item => {
        const checkbox = item.querySelector('.ent-checkbox');
        const quantityInput = item.querySelector('.ent-quantity');

        if (checkbox?.checked) {
            const price = parseFloat(checkbox.dataset.price || 0);
            const quantity = parseInt(quantityInput?.value || 1);
            total += price * quantity;
        }
    });

    const display = document.getElementById('total_price_display');
    const totalPriceInput = document.getElementById('total_price');
    const finalPriceInput = document.getElementById('final_price');

    if (display) {
        display.textContent = formatPrice(total) + ' ₽';
    }
    if (totalPriceInput) {
        totalPriceInput.value = total;
    }
    if (finalPriceInput) {
        finalPriceInput.value = total;
    }
}

// Функция для удаления билета через AJAX
function deleteTicket(itemId, deleteUrl) {
    if (!confirm('Удалить билет из заказа?')) {
        return;
    }

    // Получаем CSRF токен
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
        document.querySelector('input[name="_token"]')?.value;

    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('Ошибка безопасности. Пожалуйста, обновите страницу.', 'error');
        return;
    }

    // Показываем индикатор загрузки
    const button = document.querySelector(`.delete-ticket-btn[data-item-id="${itemId}"]`);
    if (button) {
        const originalText = button.textContent;
        button.textContent = 'Удаление...';
        button.disabled = true;
    }

    // Используем fetch для отправки DELETE запроса
    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' // Добавляем заголовок для Laravel
        },
    })
        .then(response => {
            // Восстанавливаем кнопку
            if (button) {
                button.textContent = 'Удалить';
                button.disabled = false;
            }

            if (!response.ok) {
                // Если ответ не OK, пытаемся прочитать JSON ошибки
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Ошибка при удалении билета');
                }).catch(() => {
                    throw new Error(`Ошибка ${response.status}: ${response.statusText}`);
                });
            }

            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Удаляем элемент из DOM
                const ticketItem = document.querySelector(`.current-ticket-item[data-item-id="${itemId}"]`);
                if (ticketItem) {
                    ticketItem.remove();
                    updateTotalPrice();

                    // Показываем сообщение об успехе
                    showNotification(data.message || 'Билет успешно удален из заказа', 'success');

                    // Обновляем список доступных билетов (перезагружаем страницу)
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } else {
                showNotification(data.message || 'Ошибка при удалении билета', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Ошибка при удалении билета', 'error');

            // Восстанавливаем кнопку в случае ошибки
            if (button) {
                button.textContent = 'Удалить';
                button.disabled = false;
            }
        });
}

// Вспомогательная функция для уведомлений
function showNotification(message, type = 'info') {
    // Удаляем старые уведомления
    document.querySelectorAll('.custom-notification').forEach(el => el.remove());

    // Создаем элемент уведомления
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Удаляем уведомление через 5 секунд
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', () => {
    console.log('Скрипт редактирования заказа загружен');

    // Инициализация билетов
    document.querySelectorAll('.ticket-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            updateSelectedTickets();
            updateTotalPrice();
        });
    });

    // Инициализация развлечений
    document.querySelectorAll('.ent-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const item = this.closest('.entertainment-item');
            const quantityInput = item?.querySelector('.ent-quantity');
            if (quantityInput) {
                quantityInput.disabled = !this.checked;
                if (!this.checked) {
                    quantityInput.value = 1;
                }
            }
            updateTotalPrice();
        });

        // Триггерим change для инициализации состояния
        if (cb.checked) {
            const item = cb.closest('.entertainment-item');
            const quantityInput = item?.querySelector('.ent-quantity');
            if (quantityInput) {
                quantityInput.disabled = false;
            }
        }
    });

    document.querySelectorAll('.ent-quantity').forEach(input => {
        input.addEventListener('input', updateTotalPrice);
    });

    // Поиск билетов
    const searchInput = document.getElementById('ticket-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.ticket-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = query && !text.includes(query) ? 'none' : 'flex';
            });
        });
    }

    // Обработка кнопок удаления билетов
    document.querySelectorAll('.delete-ticket-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const itemId = this.getAttribute('data-item-id');
            const deleteUrl = this.getAttribute('data-delete-url');

            if (itemId && deleteUrl) {
                deleteTicket(itemId, deleteUrl);
            } else {
                console.error('Missing data for delete:', { itemId, deleteUrl });
                showNotification('Ошибка: отсутствуют данные для удаления', 'error');
            }
        });
    });

    // Упрощенная обработка кнопки сохранения
    const saveButton = document.getElementById('save-button');
    const editOrderForm = document.getElementById('editOrderForm');

    if (saveButton && editOrderForm) {
        // Удаляем все предыдущие обработчики
        saveButton.replaceWith(saveButton.cloneNode(true));
        const newSaveButton = document.getElementById('save-button');

        newSaveButton.addEventListener('click', function(e) {
            // Простая проверка формы
            const userId = document.getElementById('user_id').value;
            if (!userId) {
                e.preventDefault();
                showNotification('Пожалуйста, выберите пользователя', 'error');
                return false;
            }

            console.log('Сохранение формы...');
            // Форма отправится обычным образом
            return true;
        });
    }

    // Первоначальный расчет
    updateSelectedTickets();
    updateTotalPrice();
});
