<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Encabezado y Botón Crear -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Banco de Preguntas</h2>
            <p class="text-sm text-slate-500">Gestiona las preguntas, opciones y categorías para las capacitaciones.</p>
        </div>
        <button wire:click="openCreateModal" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-medium transition flex items-center space-x-2 shadow-sm">
            <i class="fas fa-plus"></i>
            <span>Nueva Pregunta</span>
        </button>
    </div>

    <!-- Mensaje Flash de Éxito -->
    @if (session()->has('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm" role="alert">
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Filtros de Búsqueda -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Búsqueda por texto -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Buscar</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Pregunta o categoría..." class="w-full pl-9 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
        </div>

        <!-- Filtro por Categoría -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Categoría</label>
            <select wire:model.live="category" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Todas las categorías</option>
                @foreach($this->categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filtro por Tipo -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tipo de Pregunta</label>
            <select wire:model.live="type" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Todos los tipos</option>
                @foreach($this->questionTypes as $qType)
                    <option value="{{ $qType->value }}">{{ $qType->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filtro por Dificultad -->
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Dificultad</label>
            <select wire:model.live="difficulty" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Todas las dificultades</option>
                <option value="easy">Fácil</option>
                <option value="medium">Media</option>
                <option value="hard">Difícil</option>
            </select>
        </div>
    </div>

    <!-- Tabla de Preguntas -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="p-4">Pregunta</th>
                        <th class="p-4">Tipo</th>
                        <th class="p-4">Categoría</th>
                        <th class="p-4">Dificultad</th>
                        <th class="p-4">Puntos</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                    @forelse($this->questions as $question)
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="p-4">
                                <div class="font-medium text-slate-900">{{ $question->question }}</div>
                                @if($question->explanation)
                                    <div class="text-xs text-slate-400 mt-0.5 truncate max-w-md">Explicación: {{ $question->explanation }}</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ is_object($question->question_type) ? $question->question_type->name : $question->question_type }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="text-slate-600">{{ $question->category ?? 'General' }}</span>
                            </td>
                            <td class="p-4 capitalize">
                                @php
                                    $diffColors = [
                                        'easy' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'hard' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium border {{ $diffColors[$question->difficulty] ?? 'bg-slate-50 text-slate-600 border-slate-200' }}">
                                    {{ $question->difficulty }}
                                </span>
                            </td>
                            <td class="p-4 font-semibold text-slate-900">{{ $question->points }} pts</td>
                            <td class="p-4 text-right space-x-1">
                                <!-- Botón Editar -->
                                <button wire:click="edit({{ $question->id }})" 
                                        class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition" 
                                        title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Botón Eliminar -->
                                <button wire:click="delete({{ $question->id }})" 
                                        wire:confirm="¿Estás seguro de eliminar esta pregunta del banco global?" 
                                        class="text-rose-500 hover:text-rose-700 p-2 rounded-lg hover:bg-rose-50 transition" 
                                        title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fas fa-folder-open text-3xl text-slate-300"></i>
                                    <p class="text-sm font-medium">No se encontraron preguntas en el banco.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="p-4 border-t border-slate-200 bg-slate-50/50">
            {{ $this->questions->links() }}
        </div>
    </div>

    <!-- Modal Formulario (Crear / Editar) -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden max-h-[90vh] flex flex-col transform transition-all">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $editingQuestionId ? 'Editar Pregunta' : 'Crear Nueva Pregunta' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto space-y-5 flex-1">
                    <!-- Texto de la pregunta -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Texto de la Pregunta *</label>
                        <textarea wire:model="question_text" rows="3" placeholder="Escribe el enunciado de la pregunta..." class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                        @error('question_text') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tipo y Puntos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tipo de Pregunta *</label>
                            <select wire:model.live="question_type" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @foreach($this->questionTypes as $qType)
                                    <option value="{{ $qType->value }}">{{ $qType->name }}</option>
                                @endforeach
                            </select>
                            @error('question_type') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Puntos *</label>
                            <input type="number" step="0.5" min="0.5" wire:model="question_points" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('question_points') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Categoría y Dificultad -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Categoría</label>
                            <input type="text" wire:model="category_input" placeholder="Ej: Seguridad, Redes..." class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('category_input') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Dificultad</label>
                            <select wire:model="difficulty_input" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="easy">Fácil</option>
                                <option value="medium">Media</option>
                                <option value="hard">Difícil</option>
                            </select>
                            @error('difficulty_input') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Explicación -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Explicación (Opcional)</label>
                        <textarea wire:model="explanation" rows="2" placeholder="Explicación que se mostrará tras responder..." class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                        @error('explanation') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Sección de Opciones Dinámicas -->
                    @if($question_type !== 'long_answer')
                        <div class="border-t border-slate-100 pt-4 mt-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Opciones de Respuesta</h4>
                                @if($question_type !== 'true_false' && $question_type !== 'numeric')
                                    <button type="button" wire:click="addOption" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                        <i class="fas fa-plus mr-1"></i> Añadir opción
                                    </button>
                                @endif
                            </div>

                            @error('options') <span class="text-rose-500 text-xs block mb-2">{{ $message }}</span> @enderror

                            <div class="space-y-2.5">
                                @foreach($options as $index => $option)
                                    <div class="flex items-center space-x-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                                        <!-- Checkbox de respuesta correcta -->
                                        <div class="flex items-center">
                                            <input type="checkbox" wire:model="options.{{ $index }}.is_correct" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer" title="Marcar como correcta">
                                        </div>

                                        <!-- Input de la opción -->
                                        <div class="flex-1">
                                            <input type="{{ $question_type === 'numeric' ? 'number' : 'text' }}" 
                                                   wire:model="options.{{ $index }}.option" 
                                                   placeholder="{{ $question_type === 'numeric' ? 'Número exacto' : 'Escribe la opción ' . ($index + 1) }}" 
                                                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-white"
                                                   {{ $question_type === 'true_false' ? 'readonly' : '' }}>
                                            @error("options.{$index}.option") <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Botón eliminar opción -->
                                        @if($question_type !== 'true_false' && $question_type !== 'numeric' && count($options) > 1)
                                            <button type="button" wire:click="removeOption({{ $index }})" class="text-slate-400 hover:text-rose-600 p-1.5 rounded-lg transition" title="Eliminar opción">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end px-6 py-4 border-t border-slate-100 bg-slate-50 space-x-3">
                    <button wire:click="closeModal" type="button" class="px-4 py-2 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-100 font-medium text-sm transition">
                        Cancelar
                    </button>
                    <button wire:click="saveQuestion" type="button" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium text-sm shadow-sm transition">
                        {{ $editingQuestionId ? 'Actualizar Pregunta' : 'Guardar Pregunta' }}
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>