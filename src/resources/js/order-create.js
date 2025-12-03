// resources/js/order-create.js
// Полностью без TomSelect — чистый, быстрый, красивый

function formatPrice(price) {
    return new Intl.NumberFormat('ru-RU').format(price);
}

// Обновляет чипсы выбранных билетов + счётчик + сумму билетов
function updateSelectedTickets() {
    const checkboxes = document.querySelectorAll('.ticket-checkbox:checked');
    const countSpan = document.getElementById('selected-count');
    const sumSpan = document.getElementById('selected-sum');
    const chipsContainer = document.getElementById('selected-tickets');

    let totalTicketsPrice = 0;
    chipsContainer.innerHTML = ''; // очищаем

    checkboxes.forEach(cb => {
        const price = parseFloat(cb.dataset.price) || 0;
        totalTicketsPrice += price;

        // Создаём красивый чип
        const chip = document.createElement('div');
        chip.className = 'inline-flex items-center bg-indigo-100 text-indigo-800 rounded-full px-4 py-1.5 text-sm font-medium';
        chip.innerHTML = `
            <span>${cb.dataset.text}</span>
            <button type="button" class="ml-3 text-indigo-600 hover:text-indigo-900 focus:outline-none">
                ×
            </button>
        `;

        // Клик по крестику — снимает галочку
        chip.querySelector('button').addEventListener('click', () => {
            cb.checked = false;
            updateSelectedTickets();
        });

        chipsContainer.appendChild(chip);
    });

    countSpan.textContent = checkboxes.length;
    sumSpan.textContent = formatPrice(totalTicketsPrice);

    // Пересчитываем общую сумму всего заказа
    updateTotalPrice();
}

// Подсчёт общей суммы (билеты + развлечения)
function updateTotalPrice() {
    let total = 0;

    // Билеты
    document.querySelectorAll('.ticket-checkbox:checked').forEach(cb => {
        total += parseFloat(cb.dataset.price) || 0;
    });

    // Развлечения
    document.querySelectorAll('.entertainment-item').forEach(item => {
        const checkbox = item.querySelector('.ent-checkbox');
        const quantityInput = item.querySelector('.ent-quantity');
        if (checkbox && checkbox.checked) {
            const priceText = item.querySelector('label').textContent;
            const match = priceText.match(/([\d\s]+)₽/);
            if (match) {
                const price = parseFloat(match[1].replace(/\s/g, ''));
                const qty = parseInt(quantityInput.value) || 1;
                total += price * qty;
            }
        }
    });

    const display = document.getElementById('total_price_display');
    const hidden = document.getElementById('total_price');
    if (display && hidden) {
        const formatted = formatPrice(total);
        display.textContent = formatted + ' ₽';
        hidden.value = total.toFixed(2);
    }
}

// Поиск по билетам — работает мгновенно и точно
document.getElementById('ticket-search')?.addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();

    document.querySelectorAll('.ticket-item').forEach(label => {
        const text = label.textContent.toLowerCase();
        label.style.display = (query === '' || text.includes(query)) ? '' : 'none';
    });
});

// Всё запускается после загрузки DOM
document.addEventListener('DOMContentLoaded', function () {

    // Обработчики на чекбоксы билетов
    document.querySelectorAll('.ticket-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedTickets);
    });

    // Развлечения: включение/выключение количества
    document.querySelectorAll('.ent-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const qtyInput = this.closest('.entertainment-item').querySelector('.ent-quantity');
            if (qtyInput) {
                qtyInput.disabled = !this.checked;
                if (!this.checked) qtyInput.value = 1;
            }
            updateTotalPrice();
        });
    });

    // Изменение количества развлечений
    document.querySelectorAll('.ent-quantity').forEach(input => {
        input.addEventListener('input', updateTotalPrice);
    });

    // Инициализация при загрузке (важно для old() при ошибках валидации)
    updateSelectedTickets();
    updateTotalPrice();
});
