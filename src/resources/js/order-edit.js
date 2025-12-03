// resources/js/order-edit.js
function formatPrice(price) {
    return new Intl.NumberFormat('ru-RU').format(price);
}

function updateTotal() {
    let total = 0;

    // Текущие билеты (из HTML)
    document.querySelectorAll('[data-price]').forEach(el => {
        if (el.closest('.bg-gray-50') && !el.closest('.hidden')) {
            total += parseFloat(el.dataset.price || 0);
        }
    });

    // Новые выбранные билеты
    document.querySelectorAll('.ticket-checkbox:checked').forEach(cb => {
        total += parseFloat(cb.dataset.price || 0);
    });

    // Развлечения
    document.querySelectorAll('.ent-checkbox:checked').forEach(cb => {
        const qtyInput = cb.closest('.flex')?.querySelector('input[type="number"]');
        const qty = parseInt(qtyInput?.value) || 1;
        const price = parseFloat(cb.closest('.flex')?.querySelector('label')?.textContent.match(/([\d\s]+)₽/)?.[1].replace(/\s/g,'') || 0);
        total += price * qty;
    });

    const display = document.getElementById('total_price_display');
    const input = document.getElementById('total_price');
    if (display) display.textContent = formatPrice(total) + ' ₽';
    if (input) input.value = total;
}

// Поиск билетов
document.getElementById('ticket-search')?.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    document.querySelectorAll('.ticket-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = query === '' || text.includes(query) ? '' : 'none';
    });
});

// Чипсы выбранных билетов
function updateChips() {
    const container = document.getElementById('selected-tickets');
    const checked = document.querySelectorAll('.ticket-checkbox:checked');
    document.getElementById('selected-count').textContent = checked.length;
    container.innerHTML = '';
    checked.forEach(cb => {
        const chip = document.createElement('div');
        chip.className = 'inline-flex items-center bg-indigo-100 text-indigo-800 rounded-full px-4 py-2 text-sm font-medium';
        chip.innerHTML = `${cb.dataset.text} <button type="button" class="ml-3 text-indigo-600 hover:text-indigo-900">×</button>`;
        chip.querySelector('button').onclick = () => {
            cb.checked = false;
            updateChips();
            updateTotal();
        };
        container.appendChild(chip);
    });
}

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.ticket-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            updateChips();
            updateTotal();
        });
    });

    document.querySelectorAll('.ent-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const qty = cb.closest('.flex')?.querySelector('input[type="number"]');
            if (qty) qty.disabled = !cb.checked;
            if (!cb.checked) qty.value = 1;
            updateTotal();
        });
    });

    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', updateTotal);
    });

    updateChips();
    updateTotal();
});
