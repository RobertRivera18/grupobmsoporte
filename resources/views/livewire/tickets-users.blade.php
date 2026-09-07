<div class="space-y-5">
    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 shadow-sm transition-colors">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800 gap-3">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/40 text-amber-600 dark:text-amber-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2 2 2 0 012 2 2 2 0 01-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 01-2-2 2 2 0 012-2V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">
                        Estado de tus Tickets
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Resumen de casos abiertos vs. cerrados asignados a tu cuenta
                    </p>
                </div>
            </div>
        </div>

        <!-- Cards de Métricas KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 my-5">
            <!-- Abiertos -->
            <div class="rounded-xl border border-amber-100 dark:border-amber-950/40 bg-amber-50/50 dark:bg-amber-950/10 p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-amber-600 dark:text-amber-400 block mb-0.5">Tickets Abiertos</span>
                    <span class="text-2xl font-bold text-amber-950 dark:text-amber-200 tracking-tight" id="kpiAbiertos">0</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Cerrados -->
            <div class="rounded-xl border border-emerald-100 dark:border-emerald-950/40 bg-emerald-50/50 dark:bg-emerald-950/10 p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 block mb-0.5">Tickets Cerrados</span>
                    <span class="text-2xl font-bold text-emerald-950 dark:text-emerald-200 tracking-tight" id="kpiCerrados">0</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <!-- Total -->
            <div class="rounded-xl border border-gray-200/80 dark:border-gray-800 bg-gray-50/60 dark:bg-gray-800/40 p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-0.5">Total Asignados</span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight" id="kpiTotal">0</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-gray-200/60 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Contenedor Chart -->
        <div class="relative w-full h-[280px] pt-2" wire:ignore>
            <canvas id="userTicketsChart"></canvas>
        </div>
    </div>

    <!-- Script compatible con Livewire SPA Navigation y Dark Mode -->
    <script>
        document.addEventListener('livewire:navigated', initUserTicketsChart);
        document.addEventListener('DOMContentLoaded', initUserTicketsChart);

        function initUserTicketsChart() {
            const canvas = document.getElementById('userTicketsChart');
            if (!canvas) return;

            const existingChart = Chart.getChart(canvas);
            if (existingChart) {
                existingChart.destroy();
            }

            const labels = @json($chartData['labels']);
            const dataValues = @json($chartData['data']);

            // Mapeo dinámico de datos para los KPI
            const abiertos = dataValues[0] || 0;
            const cerrados = dataValues[1] || 0;
            const total = abiertos + cerrados;

            const elAbiertos = document.getElementById('kpiAbiertos');
            const elCerrados = document.getElementById('kpiCerrados');
            const elTotal = document.getElementById('kpiTotal');

            if (elAbiertos) elAbiertos.textContent = abiertos.toLocaleString();
            if (elCerrados) elCerrados.textContent = cerrados.toLocaleString();
            if (elTotal) elTotal.textContent = total.toLocaleString();

            const ctx = canvas.getContext('2d');
            const isDarkMode = document.documentElement.classList.contains('dark');

            const textColor = isDarkMode ? '#9CA3AF' : '#6B7280';
            const gridColor = isDarkMode ? 'rgba(55, 65, 81, 0.4)' : 'rgba(243, 244, 246, 1)';

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Tickets',
                        data: dataValues,
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.85)', // Amber 500
                            'rgba(16, 185, 129, 0.85)'  // Emerald 500
                        ],
                        borderColor: [
                            '#D97706',
                            '#059669'
                        ],
                        borderWidth: 1,
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 36,
                        maxBarThickness: 48
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
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
                                    const value = context.parsed.y;
                                    const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return ` Total: ${value} (${pct}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: textColor,
                                font: { family: 'Inter, system-ui, sans-serif', size: 12, weight: '500' }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: {
                                color: textColor,
                                stepSize: 1,
                                font: { family: 'Inter, system-ui, sans-serif', size: 11 }
                            }
                        }
                    }
                }
            });
        }
    </script>
</div>