<div>
    <div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold mb-4">Tickets por Administrador</h3>
    <canvas id="adminTicketsChart"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('adminTicketsChart').getContext('2d');

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Cantidad de Tickets',
                        data: @json($chartData['data']),
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
        });
    </script>
</div>

</div>
