<div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto space-y-6">

    <!-- Navegación & Título -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('admin.capacitacion.courses.index') }}"
                    class="hover:text-indigo-600 transition-colors">Cursos</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span>Nuevo</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Crear Nuevo Curso
            </h1>
        </div>

        <a href="{{ route('admin.capacitacion.courses.index') }}"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
            <i class="fas fa-arrow-left mr-2 text-xs"></i>
            Regresar
        </a>
    </div>

    <!-- Formulario Principal -->
    <form wire:submit.prevent="save"
        class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-800 overflow-hidden space-y-0">

        <div class="p-6 space-y-6">

            <!-- Fila: Nombre y Slug -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Nombre del curso <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" wire:model.live="name"
                        placeholder="Ej: Seguridad e Higiene Laboral"
                        class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-gray-900 border rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all @error('name') border-red-500 focus:border-red-500 @else border-gray-300 dark:border-gray-700 focus:border-indigo-500 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Slug Field (Autogestionado) -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Slug (URL)
                    </label>
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs text-gray-400 font-mono">
                            /
                        </span>
                        <input type="text" id="slug" wire:model="slug" readonly
                            class="w-full pl-7 pr-3.5 py-2.5 text-sm bg-gray-50 dark:bg-gray-800/40 border border-gray-200 dark:border-gray-800 rounded-xl text-gray-500 dark:text-gray-400 cursor-not-allowed font-mono">
                    </div>
                    @error('slug')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Descripción del curso
                </label>
                <textarea id="description" wire:model="description" rows="4"
                    placeholder="Escribe un resumen detallado sobre lo que aprenderán los alumnos..."
                    class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-gray-900 border rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all @error('description') border-red-500 focus:border-red-500 @else border-gray-300 dark:border-gray-700 focus:border-indigo-500 @enderror"></textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Upload Imagen Portada -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Imagen de portada
                </label>
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    <!-- Area Dropzone -->
                    <div class="w-full flex-1">
                        <label
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer bg-gray-50/50 dark:bg-gray-800/20 border-gray-300 dark:border-gray-700 hover:bg-gray-100/50 dark:hover:bg-gray-800/40 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-4 pb-5 text-center">
                                <i class="fas fa-cloud-arrow-up text-2xl text-gray-400 mb-1.5"></i>
                                <p class="text-xs text-gray-600 dark:text-gray-300 font-medium">Haz clic o arrastra un
                                    archivo de imagen</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">PNG, JPG, WEBP (Hasta 2MB)</p>
                            </div>
                            <input type="file" wire:model="image" class="hidden" accept="image/*" />
                        </label>
                    </div>

                    <!-- Vista previa si existe -->
                    @if ($image)
                        <div
                            class="relative w-full sm:w-32 h-32 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 shrink-0">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover"
                                alt="Vista Previa">

                            <!-- Overlay de Carga de Livewire -->
                            <div wire:loading wire:target="image"
                                class="absolute inset-0 bg-gray-900/60 backdrop-blur-xs flex items-center justify-center">
                                <i class="fas fa-spinner fa-spin text-white"></i>
                            </div>
                        </div>
                    @endif
                </div>
                @error('image')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Toggle de Estado -->
            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="status" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500/20 rounded-full dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-gray-600 peer-checked:bg-indigo-600">
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white block">Publicar
                            inmediatamente</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 block">Si está inactivo, solo los
                            administradores podrán ver este curso.</span>
                    </div>
                </label>
            </div>

        </div>

        <!-- Footer Actions -->
        <div
            class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-3">
            <a href="{{ route('admin.capacitacion.courses.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Cancelar
            </a>

            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex items-center justify-center px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 rounded-xl shadow-xs transition-all disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Guardar Curso</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <i class="fas fa-spinner fa-spin text-xs"></i> Guardando...
                </span>
            </button>
        </div>

    </form>
</div>
