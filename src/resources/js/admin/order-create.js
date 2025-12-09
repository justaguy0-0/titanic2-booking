function formatPrice(price) {
    return new Intl.NumberFormat('ru-RU').format(price);
}

// Обновленный расчет текущих билетов (для edit страницы)
function getCurrentTicketsTotal() {
    let total = 0;
    // Находим все текущие билеты заказа
    document.querySelectorAll('.current-ticket-item').forEach(item => {
        const price = parseFloat(item.dataset.price || item.getAttribute('data-price') || 0);
        total += price;
    });
    return total;
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

    // Текущие билеты (для edit)
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
        const hiddenIdInput = item.querySelector('input[type="hidden"][name*="entertainments"][name*="id"]');
        const hiddenQtyInput = item.querySelector('input[type="hidden"][name*="entertainments"][name*="quantity"]');

        if (checkbox?.checked) {
            // Используем либо явно указанную цену, либо извлекаем из текста
            let price = 0;
            if (checkbox.dataset.price) {
                price = parseFloat(checkbox.dataset.price);
            } else {
                // Пытаемся извлечь цену из текста
                const priceText = item.querySelector('label')?.textContent;
                if (priceText) {
                    const match = priceText.match(/([\d\s]+)₽/);
                    if (match) {
                        price = parseFloat(match[1].replace(/\s/g, ''));
                    }
                }
            }

            const quantity = parseInt(quantityInput?.value || 1);
            total += price * quantity;

            // Обновляем скрытые поля
            if (hiddenIdInput && checkbox.dataset.entId) {
                hiddenIdInput.value = checkbox.dataset.entId;
            }
            if (hiddenQtyInput && quantityInput) {
                hiddenQtyInput.value = quantity;
            }
        }
    });

    const display = document.getElementById('total_price_display');
    const hidden = document.getElementById('total_price');
    if (display) {
        display.textContent = formatPrice(total) + ' ₽';
    }
    if (hidden) {
        hidden.value = total;
    }
}

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', () => {
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
            cb.dispatchEvent(new Event('change'));
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

    // Первоначальный расчет
    updateSelectedTickets();
    updateTotalPrice();
});
