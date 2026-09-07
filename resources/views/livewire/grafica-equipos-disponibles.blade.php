<div class="space-y-5">
    <!-- Tarjeta Principal con Estilo TailAdmin -->
    <div class="rounded-2xl border border-gray-200/80 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 shadow-sm transition-colors">
        
        <!-- Header de la Tarjeta -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-gray-100 dark:border-gray-800 gap-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100 dark:border-indigo-900/50 text-indigo-600 dark:text-indigo-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">
                        Equipos Asignados vs. Libres
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Distribución y estado de inventario por categoría
                    </p>
                </div>
            </div>

            <!-- Badge Direccional de la Gráfica Divergente -->
            <div class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-200/60 dark:border-gray-700/60 text-xs font-medium text-gray-600 dark:text-gray-300 self-start sm:self-auto">
                <span class="flex items-center gap-1.5 text-rose-500 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    ← Libres
                </span>
                <span class="text-gray-300 dark:text-gray-700">|</span>
                <span class="flex items-center gap-1.5 text-indigo-500 font-semibold">
                    Asignados →
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                </span>
            </div>
        </div>

        <!-- Tarjetas de Métricas KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 my-5">
            <!-- KPI Libres -->
            <div class="rounded-xl border border-rose-100 dark:border-rose-950/40 bg-rose-50/50 dark:bg-rose-950/10 p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-rose-600 dark:text-rose-400 block mb-0.5">Total Libres</span>
                    <span class="text-2xl font-bold text-rose-950 dark:text-rose-200 tracking-tight" id="totalLibres">0</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- KPI Asignados -->
            <div class="rounded-xl border border-indigo-100 dark:border-indigo-950/40 bg-indigo-50/50 dark:bg-indigo-950/10 p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400 block mb-0.5">Total Asignados</span>
                    <span class="text-2xl font-bold text-indigo-950 dark:text-indigo-200 tracking-tight" id="totalAsignados">0</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- KPI Total -->
            <div class="rounded-xl border border-gray-200/80 dark:border-gray-800 bg-gray-50/60 dark:bg-gray-800/40 p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400 block mb-0.5">Total General</span>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight" id="totalEquipos">0</span>
                </div>
                <div class="w-8 h-8 rounded-lg bg-gray-200/60 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Contenedor del Canvas -->
        <div class="relative w-full h-[420px] pt-1" wire:ignore>
            <canvas id="equiposDivergentChart"></canvas>
        </div>

        <!-- Banner Explicativo del Eje Divergente -->
        <div class="mt-4 rounded-xl border border-indigo-100 dark:border-indigo-950/50 bg-indigo-50/40 dark:bg-indigo-950/20 p-3 flex items-start gap-2.5 text-xs text-indigo-900 dark:text-indigo-300">
            <svg class="w-4 h-4 text-indigo-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <span>
                <strong>Gráfico de barras divergentes:</strong> Muestra la disponibilidad por tipo de equipo. Las unidades <strong>libres</strong> se extienden hacia la izquierda en tono rosa y las <strong>asignadas</strong> hacia la derecha en índigo.
            </span>
        </div>
    </div>

    <!-- Script de Inicialización Adaptado a Livewire SPA y Chart.js -->
    <script>
        document.addEventListener('livewire:navigated', initEquiposChart);
        document.addEventListener('DOMContentLoaded', initEquiposChart);

        function initEquiposChart() {
            const canvas = document.getElementById('equiposDivergentChart');
            if (!canvas) return;

            // Prevenir duplicidad de instancias al navegar en Livewire
            const existingChart = Chart.getChart(canvas);
            if (existingChart) {
                existingChart.destroy();
            }

            // Datos inyectados desde Laravel
            const libresOriginal = @json($libres);
            const asignadosOriginal = @json($asignados);
            const labels = @json($labels);

            // Cálculo dinámico de métricas KPI
            const sumLibres = libresOriginal.reduce((a, b) => a + b, 0);
            const sumAsignados = asignadosOriginal.reduce((a, b) => a + b, 0);
            const sumTotal = sumLibres + sumAsignados;

            const elLibres = document.getElementById('totalLibres');
            const elAsignados = document.getElementById('totalAsignados');
            const elTotal = document.getElementById('totalEquipos');

            if (elLibres) elLibres.textContent = sumLibres.toLocaleString();
            if (elAsignados) elAsignados.textContent = sumAsignados.toLocaleString();
            if (elTotal) elTotal.textContent = sumTotal.toLocaleString();

            const ctx = canvas.getContext('2d');
            const isDarkMode = document.documentElement.classList.contains('dark');

            // Paleta de colores adaptativa según Dark Mode
            const textColor = isDarkMode ? '#9CA3AF' : '#6B7280';
            const gridColor = isDarkMode ? 'rgba(55, 65, 81, 0.4)' : 'rgba(243, 244, 246, 1)';

            // Mapeo a valores negativos para proyectar la barra a la izquierda
            const libresDivergent = libresOriginal.map(val => -Math.abs(val));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Libres',
                            data: libresDivergent,
                            backgroundColor: 'rgba(244, 63, 94, 0.85)', // Rose 500
                            borderColor: '#E11D48',
                            borderWidth: 1,
                            borderRadius: { topLeft: 6, bottomLeft: 6 },
                            barThickness: 20,
                            maxBarThickness: 28
                        },
                        {
                            label: 'Asignados',
                            data: asignadosOriginal,
                            backgroundColor: 'rgba(99, 102, 241, 0.85)', // Indigo 500
                            borderColor: '#4F46E5',
                            borderWidth: 1,
                            borderRadius: { topRight: 6, bottomRight: 6 },
                            barThickness: 20,
                            maxBarThickness: 28
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Usamos la leyenda personalizada del header
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
                                title: function(context) {
                                    return '📦 ' + context[0].label;
                                },
                                label: function(context) {
                                    const datasetLabel = context.dataset.label || '';
                                    const value = Math.abs(context.parsed.x);
                                    const percentage = sumTotal > 0 ? ((value / sumTotal) * 100).toFixed(1) : 0;
                                    return ` ${datasetLabel}: ${value} unidades (${percentage}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'Inter, system-ui, sans-serif',
                                    size: 11
                                },
                                callback: function(value) {
                                    return Math.abs(value);
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'Inter, system-ui, sans-serif',
                                    size: 11,
                                    weight: '500'
                                },
                                padding: 8
                            }
                        }
                    }
                }
            });
        }
    </script>
</div>