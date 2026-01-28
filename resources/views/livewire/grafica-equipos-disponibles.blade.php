<div class="bg-white shadow-md rounded-2xl p-4 sm:p-6 w-full max-w-5xl mx-auto">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 text-center mb-4">
        Equipos Asignados vs Libres por Tipo
    </h2>

    <div class="relative w-full" style="height: 400px;">
        <canvas id="equiposChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('equiposChart').getContext('2d');
            if (!ctx) return;
            const libres = @json($libres).map(value => -Math.abs(value));

            const data = {
                labels: @json($labels),
                datasets: [
                    {
                        label: 'Libres',
                        data: libres,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgba(220, 38, 38, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    },
                    {
                        label: 'Asignados',
                        data: @json($asignados),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    }
                ]
            };

            let chartInstance = null;

            function createChart() {
                const isMobile = window.innerWidth < 640;
                
                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: isMobile ? 'bottom' : 'right',
                                labels: {
                                    padding: isMobile ? 10 : 15,
                                    font: {
                                        size: isMobile ? 11 : 12
                                    }
                                }
                            },
                            title: {
                                display: !isMobile,
                                text: 'Equipos Asignados (Derecha) vs Libres (Izquierda)',
                                font: {
                                    size: 14
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += Math.abs(context.parsed.x);
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return Math.abs(value);
                                    },
                                    font: {
                                        size: isMobile ? 10 : 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            y: {
                                ticks: {
                                    font: {
                                        size: isMobile ? 10 : 12
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        barThickness: isMobile ? 20 : 30,
                        maxBarThickness: 40
                    }
                };

                if (chartInstance) {
                    chartInstance.destroy();
                }

                chartInstance = new Chart(ctx, config);
            }           
            createChart();
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    createChart();
                }, 250);
            });
        });
    </script>
</div>