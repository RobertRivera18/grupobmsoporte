<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-right-from-bracket text-blue-600"></i>
            Solicitud de Salida de Equipos
        </h2>
        <p class="text-sm text-gray-500">
            Selecciona el equipo y completa la información requerida.
        </p>
    </div>

    {{-- Mensajes --}}
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg border border-green-300">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg border border-red-300">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="solicitarSalida" class="space-y-6">

        {{-- Usuario --}}
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl">
            <label class="block text-xs text-gray-500 mb-1">Usuario solicitante</label>
            <div class="flex items-center gap-2">
                <i class="fas fa-user text-gray-400"></i>
                <span class="font-medium text-gray-800 dark:text-white">
                    {{ auth()->user()->name }}
                </span>
            </div>
        </div>

        {{-- Equipos --}}
        <div>
            <label class="block text-sm font-semibold mb-3 text-gray-700 dark:text-gray-300">
                Selecciona un equipo
            </label>

            <div class="grid gap-3">
                @foreach ($equipos as $equipo)
                    <label 
                        class="group border rounded-xl p-4 cursor-pointer transition-all duration-200
                        hover:shadow-md hover:border-blue-400
                        {{ $equipo_id == $equipo->id ? 'border-blue-500 bg-blue-50 dark:bg-gray-700' : 'border-gray-200 dark:border-gray-600' }}">

                        <div class="flex items-start gap-3">

                            {{-- Radio --}}
                            <input type="radio"
                                wire:model="equipo_id"
                                value="{{ $equipo->id }}"
                                class="mt-1 accent-blue-600">

                            {{-- Icono --}}
                            <div class="text-blue-500 text-lg">
                                <i class="fas fa-laptop"></i>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1">

                                <div class="flex justify-between items-center">
                                    <p class="font-semibold text-gray-800 dark:text-white">
                                        {{ $equipo->nombre }}
                                    </p>

                                    <span class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">
                                        {{ $equipo->serie ?? 'SIN COD' }}
                                    </span>
                                </div>

                                <div class="text-sm text-gray-500 mt-1">
                                    {{ $equipo->marca }} • {{ $equipo->modelo }}
                                </div>

                                <div class="text-xs text-gray-400">
                                    Serie: {{ $equipo->serie }}
                                </div>

                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            @error('equipo_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Fechas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Fecha de salida
                </label>
                <input type="date"
                    wire:model="fecha_salida_solicitada"
                    class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                @error('fecha_salida_solicitada')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Retorno estimado
                </label>
                <input type="date"
                    wire:model="fecha_retorno_estimada"
                    class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                @error('fecha_retorno_estimada')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

        </div>

        {{-- Motivo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Motivo de la salida
            </label>

            <textarea wire:model="motivo"
                rows="3"
                class="w-full mt-1 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="Describe el motivo de la salida del equipo..."></textarea>

            @error('motivo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        {{-- Botón --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-md">

                <i class="fas fa-paper-plane"></i>
                Enviar Solicitud
            </button>
        </div>

    </form>
</div>