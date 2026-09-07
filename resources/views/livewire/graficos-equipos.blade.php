<div class="space-y-4">
    <!-- Tarjeta Principal de la Gráfica -->
    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-700/60 bg-white dark:bg-gray-800 p-5 shadow-sm">
        
        <!-- Header de la Tarjeta -->
        <div class="flex items-center justify-between pb-4 mb-2 border-b border-gray-100 dark:border-gray-700/60">
            <div>
                <h4 class="text-base font-bold text-gray-900 dark:text-white">Distribución por Tipo de Equipo</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">Proporción general de inventario registrado</p>
            </div>
            <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-sm">
                <i class="fas fa-chart-pie"></i>
            </div>
        </div>

        <!-- Contenedor del Canvas con wire:ignore para evitar reseteos de Livewire -->
        <div class="relative w-full max-w-xs mx-auto py-2" wire:ignore>
            <canvas id="tipoEquiposChart" class="w-full h-auto"></canvas>
        </div>
    </div>

    <!-- Carga del CDN de Chart.js (Opcional si ya está en tu layout) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script compatible con Livewire 3 y navegación AJAX -->
    <script>
        document.addEventListener('livewire:navigated', initTipoEquiposChart);
        document.addEventListener('DOMContentLoaded', initTipoEquiposChart);

        function initTipoEquiposChart() {
            const canvas = document.getElementById('tipoEquiposChart');
            if (!canvas) return;

            // Destruir instancia previa si existe (evita errores al re-renderizar Livewire)
            const existingChart = Chart.getChart(canvas);
            if (existingChart) {
                existingChart.destroy();
            }

            const ctx = canvas.getContext('2d');
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#9CA3AF' : '#4B5563';
            const borderColor = isDarkMode ? '#1F2937' : '#FFFFFF';

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        data: @json($values),
                        backgroundColor: [
                            '#6366F1', // Indigo
                            '#0EA5E9', // Sky
                            '#10B981', // Emerald
                            '#F59E0B', // Amber
                            '#EC4899', // Pink
                            '#8B5CF6', // Violet
                            '#14B8A6'  // Teal
                        ],
                        borderWidth: 2,
                        borderColor: borderColor,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '70%', // Dona más delgada y elegante estilo TailAdmin
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                padding: 16,
                                font: {
                                    family: 'Inter, system-ui, sans-serif',
                                    size: 11,
                                    weight: '500'
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#111827' : '#FFFFFF',
                            titleColor: isDarkMode ? '#F3F4F6' : '#111827',
                            bodyColor: isDarkMode ? '#D1D5DB' : '#374151',
                            borderColor: isDarkMode ? '#374151' : '#E5E7EB',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.raw;
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return ` ${context.label}: ${value} unit. (${percent}%)`;
                                }
                            }
                        },
                        title: {
                            display: false // Oculto porque ahora usamos el header de Tailwind
                        }
                    }
                }
            });
        }
    </script>
</div>