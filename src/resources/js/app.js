/* Bootstrap JS */
import 'bootstrap';

/* Alpine.js */
import Alpine from 'alpinejs';

/* Tom select */
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

// Глобальная инициализация TomSelect на всех .tomselect
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tomselect').forEach(el => {
        if (el.tomselect) return;

        new TomSelect(el, {
            plugins: ['remove_button'],
            placeholder: 'Поиск билетов...',
            maxOptions: 100,
        });
    });
});

window.Alpine = Alpine;
Alpine.start();
