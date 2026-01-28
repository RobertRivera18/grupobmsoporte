<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold mb-4">Estado de tus Tickets</h3>

    <canvas id="userTicketsChart"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('userTicketsChart').getContext('2d');

            const data = {
                labels: @json($chartData['labels']), // ['Abiertos', 'Cerrados']
                datasets: [{
                    label: 'Tickets',
                    data: @json($chartData['data']), // [cantidad_abiertos, cantidad_cerrados]
                    backgroundColor: ['#f59e0b', '#10b981'], // Amarillo y verde
                    borderColor: ['#d97706', '#059669'],
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Resumen de Tickets Asignados'
                        }
                    }
                }
            };

            new Chart(ctx, config);
        });
    </script>
</div>
