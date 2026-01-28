@php
    $canvasId = 'graficaIndicador_' . $anio->id;
@endphp

<div
    x-data="graficaLivewire(
        @js($labels),
        @js($valores),
        {{ $metaBase }},
        '{{ $canvasId }}'
    )"
    x-init="init()"
>
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        📊 Gráfica Indicador {{ $frecuencia }}
    </h3>

    <canvas id="{{ $canvasId }}"></canvas>

    <script>
        function graficaLivewire(labels, valores, meta, canvasId) {
            return {
                chart: null,
                labels,
                valores,
                meta,
                canvasId,

                init() {
                    this.renderChart();
                },

                renderChart() {
                    const ctx = document.getElementById(this.canvasId);

                    if (!ctx) return;

                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.labels,
                            datasets: [
                                {
                                    label: "Valores",
                                    data: this.valores,
                                    borderWidth: 3,
                                    tension: 0.4,
                                    spanGaps: true,
                                    pointRadius: 4
                                },
                                {
                                    label: "Meta",
                                    data: this.labels.map(() => this.meta),
                                    borderWidth: 2,
                                    borderDash: [6, 6]
                                }
                            ]
                        }
                    });
                }
            }
        }
    </script>
</div>
