<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold text-gray-700 mb-4 text-center">
        📊 Gráfico de Recargas Mensuales
    </h2>

    <div class="relative h-64">
        <canvas id="graficoRecargas"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('graficoRecargas').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Total de Recargas ($)',
                        data: @json($totales),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)', // Azul principal
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(37, 99, 235, 0.9)',
                        hoverBorderColor: 'rgba(29, 78, 216, 1)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Totales de Recarga por Mes',
                            color: '#111827',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: { bottom: 10 }
                        },
                        tooltip: {
                            usePointStyle: true,
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleColor: '#f9fafb',
                            bodyColor: '#f9fafb',
                            borderWidth: 1,
                            borderColor: 'rgba(59,130,246,0.8)',
                            displayColors: false,
                            padding: 10,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            callbacks: {
                                title: function(context) {
                                    return 'Mes: ' + context[0].label;
                                },
                                label: function(context) {
                                    return '💰 Total: $' + context.raw.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#374151',
                                callback: function(value) {
                                    return '$' + value;
                                }
                            },
                            grid: {
                                color: 'rgba(229, 231, 235, 0.4)'
                            }
                        },
                        x: {
                            ticks: { color: '#374151' },
                            grid: { display: false }
                        }
                    },
                    animation: {
                        duration: 1200,
                        easing: 'easeOutBounce'
                    }
                }
            });
        });
    </script>
</div>
