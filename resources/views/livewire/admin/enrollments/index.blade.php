<div class="p-6 max-w-7xl mx-auto space-y-6">
    
    <div>
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">
            Matriculación de Estudiantes
        </h1>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Gestión de inscripciones y progreso
        </p>
    </div>

    @if (session()->has('message'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Formulario --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200/80 dark:border-gray-800 shadow-sm h-fit space-y-4">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Nueva Matrícula</h2>

            <form wire:submit.prevent="enroll" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Estudiante</label>
                    <select wire:model="user_id" class="w-full rounded-xl text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">-- Seleccionar Estudiante --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Curso</label>
                    <select wire:model="course_id" class="w-full rounded-xl text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">-- Seleccionar Curso --</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                    @error('course_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                    <select wire:model="status" class="w-full rounded-xl text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                    Matricular Estudiante
                </button>
            </form>
        </div>

        {{-- Tabla de Registros --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200/80 dark:border-gray-800 shadow-sm space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por alumno o email..." class="rounded-xl text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                
                <select wire:model.live="selectedCourseFilter" class="rounded-xl text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">Todos los Cursos</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 uppercase font-bold text-[10px]">
                        <tr>
                            <th class="p-3">Estudiante</th>
                            <th class="p-3">Curso</th>
                            <th class="p-3 text-center">Progreso</th>
                            <th class="p-3 text-center">Estado</th>
                            <th class="p-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($enrollments as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20">
                                <td class="p-3">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $item->user->name }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $item->user->email }}</div>
                                </td>
                                <td class="p-3 font-medium text-gray-700 dark:text-gray-300">
                                    {{ $item->course->name }}
                                </td>
                                <td class="p-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                        {{ number_format($item->progress, 0) }}%
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <button wire:click="toggleStatus({{ $item->id }})" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition-colors {{ $item->status ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300' }}">
                                        {{ $item->status ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>
                                <td class="p-3 text-right">
                                    <button wire:click="unenroll({{ $item->id }})" wire:confirm="¿Deseas cancelar la matrícula?" class="text-rose-500 hover:text-rose-700 p-1">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-400">No hay matriculados registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $enrollments->links() }}
            </div>
        </div>

    </div>
</div>