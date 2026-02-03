<div class="relative space-y-5 mt-20">

    {{-- BUSCADOR --}}
    <div class="relative">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <i class="fas fa-search"></i>
        </span>
  
        <input
            type="text"
            wire:model.live="search"
            placeholder="Buscar colaborador por nombre o cédula"
            class="w-full pl-10 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition
                   disabled:bg-gray-100 disabled:cursor-not-allowed"
            @if($colaborador) disabled @endif
        />
    </div>

    {{-- RESULTADOS --}}
    @if ($search && $this->colaboradores->count())
        <ul
            class="absolute z-30 w-full bg-white border rounded-xl shadow-lg max-h-56 overflow-auto divide-y"
        >
            @foreach ($this->colaboradores as $user)
                <li
                    wire:click="seleccionarColaborador({{ $user->id }})"
                    class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition"
                >
                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->cedula }}</p>
                </li>
            @endforeach
        </ul>
    @endif

    {{-- COLABORADOR SELECCIONADO --}}
    @if ($colaborador)
        <div class="flex items-center justify-between bg-blue-50 border border-blue-200 p-3 rounded-xl">
            <div class="flex items-center gap-2">
                <span class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center">
                    <i class="fas fa-user"></i>
                </span>
                <div>
                    <p class="font-semibold text-blue-800">{{ $colaborador->name }}</p>
                    <p class="text-xs text-blue-600">{{ $colaborador->cedula }}</p>
                    
                </div>
            </div>

            <button
                wire:click="limpiarColaborador"
                class="text-sm text-red-600 hover:text-red-800 transition"
            >
                <i class="fas fa-times"></i> Quitar
            </button>
        </div>
    @endif

    {{-- MENSAJES --}}
    @if(!$colaborador)
        <div class="text-center text-gray-500 text-sm flex flex-col items-center gap-2">
            <i class="fas fa-hand-point-up text-lg"></i>
            <p>Selecciona un colaborador para ver sus actas firmadas</p>
        </div>
    @elseif($actas->isEmpty())
        <div class="text-center text-gray-500 text-sm flex flex-col items-center gap-2">
            <i class="fas fa-folder-open text-lg"></i>
            <p>{{ $colaborador->name }} no tiene actas firmadas</p>
        </div>
    @endif

    {{-- LISTADO ACTAS --}}
    <div class="space-y-3">
        @foreach ($actas as $acta)
            <div
                class="bg-white border rounded-xl p-4 shadow-sm hover:shadow-md transition flex justify-between items-center"
            >
                <div>
                    <p class="font-semibold text-gray-800">
                        Acta de {{ ucfirst($acta->tipo) }}
                    </p>

                    <p class="text-sm text-gray-500 flex items-center gap-1">
                        <i class="far fa-calendar"></i>
                        {{ $acta->firmado_en->format('d/m/Y H:i') }}
                    </p>
                </div>

                <a
                    href="{{ asset($acta->ruta_docx) }}"
                    target="_blank"
                    class="flex items-center gap-1 px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    <i class="far fa-eye"></i> Ver
                </a>
            </div>
        @endforeach
    </div>

</div>
