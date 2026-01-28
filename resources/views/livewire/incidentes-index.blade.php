<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-8 space-y-8">

    <!-- HEADER -->
    <div class="border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            📋 Registrar Incidencia
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Complete la información para registrar una nueva incidencia
            En caso de ser perdida de credencial se debe adjuntar la respectiva denuncia
        </p>
    </div>

    <!-- ALERT SUCCESS -->
    @if (session('success'))
        <div class="p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="guardarIncidencia" class="space-y-6">

        <!-- CÉDULA -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Cédula del usuario
            </label>

            <div class="flex gap-2">
                <input
                    type="text"
                    wire:model.defer="cedula"
                    placeholder="Ingrese la cédula"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                >

                <button
                    type="button"
                    wire:click="buscarUsuario"
                    class="px-4 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                >
                    Buscar
                </button>
            </div>

            @error('cedula')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- USUARIO ENCONTRADO -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Usuario
            </label>

            <input
                type="text"
                wire:model="nombre_usuario"
                readonly
                class="w-full rounded-lg bg-gray-100 border-gray-300 cursor-not-allowed"
            >

            @if ($usuarioEncontrado)
                <p class="text-sm text-green-600 mt-1 flex items-center gap-1">
                    ✔ Usuario encontrado:
                    <strong>{{ $usuarioEncontrado->name }}</strong>
                </p>
            @endif
        </div>

        <!-- NOMBRE -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Nombre de la incidencia
            </label>
            <input
                type="text"
                wire:model.defer="nombre"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
            @error('nombre')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- DETALLE -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Detalle
            </label>
            <textarea
                rows="4"
                wire:model.defer="detalle"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 resize-none"
            ></textarea>
            @error('detalle')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- FECHA -->
        <div class="max-w-xs">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Fecha
            </label>
            <input
                type="date"
                wire:model.defer="fecha"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
            @error('fecha')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- ARCHIVOS -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Adjuntar archivos
            </label>

            <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center">
                <input
                    type="file"
                    wire:model="archivos"
                    multiple
                    class="w-full text-sm text-gray-600"
                >
                <p class="text-xs text-gray-500 mt-2">
                    JPG, PNG, PDF, DOCX — máx. 2MB
                </p>
            </div>

            @error('archivos.*')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- PREVIEW ARCHIVOS -->
        @if ($archivos)
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-2">
                    Archivos seleccionados
                </p>

                <div class="flex flex-wrap gap-4">
                    @foreach ($archivos as $archivo)
                        <div class="w-24 h-24 border rounded-xl overflow-hidden flex items-center justify-center bg-gray-50">
                            @if (in_array($archivo->extension(), ['pdf', 'docx']))
                                <span class="text-xs font-bold text-gray-600">
                                    {{ strtoupper($archivo->extension()) }}
                                </span>
                            @else
                                <img src="{{ $archivo->temporaryUrl() }}" class="object-cover w-full h-full">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- BOTÓN -->
        <div class="pt-4">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full md:w-auto px-6 py-3 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition flex items-center gap-2"
            >
                <span wire:loading.remove>Registrar incidencia</span>
                <span wire:loading>Guardando...</span>
            </button>
        </div>
    </form>


    <script>
        window.addEventListener('swal', event => {
            Swal.fire({
                icon: event.detail.icon ?? 'success',
                title: event.detail.title ?? 'Correcto',
                text: event.detail.text ?? 'Incidencia Registrada con exito',
                timer: event.detail.timer ?? 2000,
                showConfirmButton: false
            });
        });
    </script>

</div>
