<div class="space-y-4">
    <!-- Tarjeta Principal -->
    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-5 shadow-sm">
        
        <!-- Header de la Tarjeta -->
        <div class="flex items-center justify-between pb-4 mb-3 border-b border-gray-100 dark:border-gray-700/60">
            <div>
                <h4 class="text-base font-bold text-gray-900 dark:text-white">Tickets por Administrador</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">Carga de trabajo y solicitudes asignadas</p>
            </div>
            <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-sm">
                <i class="fas fa-chart-column"></i>
            </div>
        </div>

        <!-- Contenedor del Canvas -->
        <div class="relative w-full min-h-[280px] pt-2" wire:ignore>
            <canvas id="adminTicketsChart"></canvas>
        </div>
    </div>

    <!-- Script compatible con Livewire 3 y SPA -->
    <script>
        document.addEventListener('livewire:navigated', initAdminTicketsChart);
        document.addEventListener('DOMContentLoaded', initAdminTicketsChart);

        function initAdminTicketsChart() {
            const canvas = document.getElementById('adminTicketsChart');
            if (!canvas) return;

            // Destruir instancia previa si existe para evitar conflictos
            const existingChart = Chart.getChart(canvas);
            if (existingChart) {
                existingChart.destroy();
            }

            const ctx = canvas.getContext('2d');
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Colores adaptativos según el tema
            const textColor = isDarkMode ? '#9CA3AF' : '#6B7280';
            const gridColor = isDarkMode ? 'rgba(55, 65, 81, 0.5)' : 'rgba(243, 244, 246, 1)';
            
            // Gradiente para las barras
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.9)');  // Indigo 500
            gradient.addColorStop(1, 'rgba(129, 140, 248, 0.3)'); // Indigo 400 atenuado

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Cantidad de Tickets',
                        data: @json($chartData['data']),
                        backgroundColor: gradient,
                        borderColor: '#6366F1',
                        borderWidth: 1.5,
                        borderRadius: 8, // Esquinas redondeadas arriba estilo TailAdmin
                        borderSkipped: 'bottom',
                        barThickness: 28, // Ancho de barra elegante
                        maxBarThickness: 36
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Oculto para vista limpia de dashboard
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#111827' : '#FFFFFF',
                            titleColor: isDarkMode ? '#F3F4F6' : '#111827',
                            bodyColor: isDarkMode ? '#D1D5DB' : '#374151',
                            borderColor: isDarkMode ? '#374151' : '#E5E7EB',
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return ` Tickets: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'Inter, system-ui, sans-serif',
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: {
                                color: textColor,
                                stepSize: 1,
                                precision: 0,
                                font: {
                                    family: 'Inter, system-ui, sans-serif',
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</div>