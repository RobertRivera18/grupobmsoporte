<div class="mt-6 space-y-4">

    @if(!$empleado)
        <div class="text-gray-500 text-sm text-center">
            👆 Selecciona un empleado para ver sus actas firmadas
        </div>
    @endif

    @if($empleado && $actas->isEmpty())
        <div class="text-gray-500 text-sm text-center">
            📄 {{ $empleado['name'] }} no tiene actas firmadas
        </div>
    @endif

    @foreach ($actas as $acta)
        <div class="bg-white border rounded-lg p-4 shadow-sm flex justify-between items-center">
            <div>
                <p class="font-semibold text-gray-800">
                    Acta de {{ ucfirst($acta->tipo) }}
                </p>

                <p class="text-sm text-gray-500">
                    📅 {{ $acta->fecha_firma->format('d/m/Y H:i') }}
                </p>
            </div>

            <a
                href="{{ asset($acta->ruta_pdf ?? $acta->ruta_docx) }}"
                target="_blank"
                class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                👁️ Ver
            </a>
        </div>
    @endforeach

</div>
