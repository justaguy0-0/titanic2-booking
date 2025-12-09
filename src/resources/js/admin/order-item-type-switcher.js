document.addEventListener('DOMContentLoaded', () => {
    const ticketSection = document.querySelector('.ticket-section');
    const entertainmentSection = document.querySelector('.entertainment-section');
    const radios = document.querySelectorAll('input[name="item_type"]');

    if (!ticketSection || !entertainmentSection || radios.length === 0) {
        return;
    }

    function updateVisibility() {
        const selectedValue = document.querySelector('input[name="item_type"]:checked')?.value || 'ticket';

        if (selectedValue === 'ticket') {
            ticketSection.classList.remove('hidden');
            entertainmentSection.classList.add('hidden');
        } else {
            ticketSection.classList.add('hidden');
            entertainmentSection.classList.remove('hidden');
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', updateVisibility);
    });

    updateVisibility();
});
