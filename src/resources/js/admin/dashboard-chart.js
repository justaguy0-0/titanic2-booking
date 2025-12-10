document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('sales-chart');
    if (!canvas) return;

    const labels = JSON.parse(canvas.dataset.labels);
    const ordersData = JSON.parse(canvas.dataset.orders);
    const revenueData = JSON.parse(canvas.dataset.revenue);

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Количество заказов',
                    data: ordersData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    yAxisID: 'y',
                },
                {
                    label: 'Выручка (₽)',
                    data: revenueData,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.3,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 1) {
                                return context.dataset.label + ': ' +
                                    new Intl.NumberFormat('ru-RU').format(context.parsed.y) + ' ₽';
                            }
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: 'Количество заказов' }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Выручка (₽)' }
                }
            }
        }
    });
});
