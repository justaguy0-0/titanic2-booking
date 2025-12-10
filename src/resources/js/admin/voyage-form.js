document.addEventListener('DOMContentLoaded', function () {
    const departureInput = document.getElementById('departure_date');
    const arrivalInput = document.getElementById('arrival_date');
    const travelTimeInput = document.getElementById('travel_time');

    function calculateTravelTime() {
        if (!departureInput.value || !arrivalInput.value) {
            travelTimeInput.value = '';
            return;
        }

        const departure = new Date(departureInput.value);
        const arrival = new Date(arrivalInput.value);

        if (arrival <= departure) {
            travelTimeInput.value = '';
            return;
        }

        const diffMs = arrival - departure;
        const diffHours = Math.ceil(diffMs / (1000 * 60 * 60));
        travelTimeInput.value = diffHours;
    }

    departureInput.addEventListener('change', calculateTravelTime);
    arrivalInput.addEventListener('change', calculateTravelTime);

    if (departureInput.value && arrivalInput.value) {
        calculateTravelTime();
    }
});
