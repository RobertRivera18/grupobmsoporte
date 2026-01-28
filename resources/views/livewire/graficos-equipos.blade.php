<div>
    <canvas id="tipoEquiposChart" width="200" height="200"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('tipoEquiposChart').getContext('2d');
            const tipoEquiposChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        data: @json($values),
                        backgroundColor: [
                            '#FF4C4C', '#4C6EFF', '#00D1D1',
                            '#FFC107', '#8E44AD', '#10B981', '#F59E0B'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.raw;
                                    const percent = ((value / total) * 100).toFixed(2);
                                    return `${context.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribución por Tipo de Equipo'
                        }
                    }
                }
            });
        });
    </script>
</div>
