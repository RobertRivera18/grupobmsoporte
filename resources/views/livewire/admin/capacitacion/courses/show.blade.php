<div x-data="{ openModuleModal: false, openLessonModal: false }" @close-modals.window="openModuleModal = false; openLessonModal = false"
    class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">

    <!-- CDN Quill Styles -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

    {{-- Header --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-200 dark:border-gray-800">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('admin.capacitacion.courses.index') }}"
                    class="hover:text-indigo-600 transition-colors">Cursos</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="truncate max-w-[200px]">{{ $course->name }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Detalles del Curso
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.capacitacion.courses.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <i class="fas fa-arrow-left mr-2 text-xs"></i> Volver
            </a>
            <a href="{{ route('admin.capacitacion.courses.edit', $course) }}"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-xs transition-all">
                <i class="fas fa-edit text-xs mr-2"></i> Editar Curso
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info General --}}
        <div class="lg:col-span-1 space-y-6">
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-800 overflow-hidden">
                <div class="relative w-full h-48 bg-gray-100 dark:bg-gray-800">
                    @if ($course->image)
                        <img src="{{ str_starts_with($course->image, 'http') ? $course->image : asset('storage/' . $course->image) }}"
                            alt="{{ $course->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-image text-3xl mb-2"></i>
                            <span class="text-xs">Sin portada asignada</span>
                        </div>
                    @endif
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $course->name }}</h2>
                        <p class="text-xs font-mono text-gray-400 dark:text-gray-500 mt-0.5">/{{ $course->slug }}</p>
                    </div>

                    @if ($course->description)
                        <div class="pt-3 border-t border-gray-100 dark:border-gray-800">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">Descripción
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                {{ $course->description }}</p>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-800 grid grid-cols-2 gap-4">
                        <div
                            class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 text-center">
                            <span
                                class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $course->modules->count() }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Módulos</span>
                        </div>
                        <div
                            class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800 text-center">
                            <span
                                class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $course->modules->sum(fn($m) => $m->lessons->count()) }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Lecciones</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Módulos y Lecciones --}}
        <div class="lg:col-span-2 space-y-6">
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-800 p-6 space-y-4">

                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-layer-group text-indigo-500"></i> Estructura del Curso
                    </h3>
                    <button @click="openModuleModal = true; $wire.resetModuleForm()" type="button"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-1.5"></i> Nuevo Módulo
                    </button>


                </div>

                <div class="space-y-3">
                    @forelse($course->modules as $index => $module)
                        <div x-data="{ open: true }"
                            class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden bg-gray-50/30 dark:bg-gray-800/20">
                            <div
                                class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <button @click="open = !open" type="button" class="flex items-center gap-3 flex-1">
                                    <span
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 font-bold text-xs">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $module->name }}
                                        </h4>
                                        <p class="text-xs text-gray-400">
                                            {{ $module->lessons->count() }}
                                            {{ Str::plural('lección', $module->lessons->count()) }}
                                            @if ($module->quizzes->count() > 0)
                                                • {{ $module->quizzes->count() }}
                                                {{ Str::plural('evaluación', $module->quizzes->count()) }}
                                            @endif
                                        </p>
                                    </div>
                                    <i class="fas fa-chevron-down text-xs text-gray-400 ml-2 transition-transform duration-200"
                                        :class="{ 'rotate-180': open }"></i>
                                </button>

                                <div class="flex items-center gap-1.5 ml-4">

                                    {{-- Añadir Lección --}}
                                    <button @click="openLessonModal = true; $wire.openLessonModal({{ $module->id }})"
                                        title="Añadir Lección"
                                        class="p-1.5 text-xs text-emerald-600 dark:text-emerald-400
               hover:bg-emerald-50 dark:hover:bg-emerald-950/50
               rounded-md transition-colors">

                                        <i class="fas fa-plus-circle"></i>

                                    </button>

                                    {{-- Quiz Builder --}}
                                    <a href="{{ route('admin.capacitacion.modules.quiz', ['module' => $module->id]) }}"
                                        title="{{ $module->quizzes->isNotEmpty() ? 'Gestionar Quiz' : 'Crear Quiz' }}"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-semibold
               text-indigo-600 dark:text-indigo-400
               bg-indigo-50 dark:bg-indigo-950/50
               hover:bg-indigo-100 dark:hover:bg-indigo-900/60
               rounded-lg transition-colors">

                                        <i class="fas fa-list-check"></i>

                                        {{ $module->quizzes->isNotEmpty() ? 'Gestionar Quiz' : 'Crear Quiz' }}

                                    </a>

                                    {{-- Editar Módulo --}}
                                    <button @click="openModuleModal = true; $wire.editModule({{ $module->id }})"
                                        title="Editar Módulo"
                                        class="p-1.5 text-xs text-indigo-600 dark:text-indigo-400
               hover:bg-indigo-50 dark:hover:bg-indigo-950/50
               rounded-md transition-colors">

                                        <i class="fas fa-pen"></i>

                                    </button>

                                    {{-- Eliminar Módulo --}}
                                    <button wire:click="deleteModule({{ $module->id }})"
                                        wire:confirm="¿Eliminar este módulo y sus lecciones?" title="Eliminar Módulo"
                                        class="p-1.5 text-xs text-rose-600 dark:text-rose-400
               hover:bg-rose-50 dark:hover:bg-rose-950/50
               rounded-md transition-colors">

                                        <i class="fas fa-trash-can"></i>

                                    </button>

                                </div>
                            </div>

                            <div x-show="open" x-collapse x-cloak
                                class="p-4 pt-0 border-t border-gray-100 dark:border-gray-800/60 bg-white dark:bg-gray-900 space-y-2">
                                @if ($module->lessons->isNotEmpty())
                                    <div class="pt-3 space-y-1.5">
                                        <p
                                            class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                            Lecciones</p>
                                        @foreach ($module->lessons as $lesson)
                                            <div
                                                class="flex items-center justify-between p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/40 text-xs border border-gray-100 dark:border-gray-800">
                                                <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                                    <i class="fas fa-play-circle text-indigo-500 text-sm"></i>
                                                    <span class="font-medium">{{ $lesson->title }}</span>

                                                    <div class="flex items-center gap-1.5 ml-2">
                                                        @if ($lesson->content)
                                                            <span title="Teoría / Texto incluido"
                                                                class="px-1.5 py-0.5 text-[10px] bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded">
                                                                <i class="fas fa-align-left"></i>
                                                            </span>
                                                        @endif
                                                        @if ($lesson->video_url)
                                                            <span title="Video incluido"
                                                                class="px-1.5 py-0.5 text-[10px] bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded">
                                                                <i class="fas fa-video"></i>
                                                            </span>
                                                        @endif
                                                        @if ($lesson->pdf_path)
                                                            <a href="{{ asset('storage/' . $lesson->pdf_path) }}"
                                                                target="_blank" title="PDF Adjunto"
                                                                class="px-1.5 py-0.5 text-[10px] bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded hover:underline">
                                                                <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <button
                                                        @click="openLessonModal = true; $wire.editLesson({{ $lesson->id }})"
                                                        class="p-1 text-gray-400 hover:text-indigo-600 rounded">
                                                        <i class="fas fa-pen text-[11px]"></i>
                                                    </button>
                                                    <button wire:click="deleteLesson({{ $lesson->id }})"
                                                        wire:confirm="¿Eliminar esta lección?"
                                                        class="p-1 text-gray-400 hover:text-rose-600 rounded">
                                                        <i class="fas fa-trash-can text-[11px]"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($module->quizzes->isNotEmpty())
                                    <div class="pt-2 space-y-1.5">
                                        <p
                                            class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                            Evaluaciones</p>
                                        @foreach ($module->quizzes as $quiz)
                                            <div
                                                class="flex items-center justify-between p-2.5 rounded-lg bg-amber-50/50 dark:bg-amber-950/20 text-xs text-amber-800 dark:text-amber-300 border border-amber-200/60 dark:border-amber-900/40">
                                                <div class="flex items-center gap-2.5">
                                                    <i class="fas fa-clipboard-question text-amber-500 text-sm"></i>
                                                    <span class="font-medium">{{ $quiz->title ?? $quiz->name }}</span>
                                                </div>
                                                <span
                                                    class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-900/60">Quiz</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if ($module->lessons->isEmpty() && $module->quizzes->isEmpty())
                                    <p class="text-xs text-gray-400 italic py-2 text-center">Este módulo aún no
                                        contiene lecciones ni evaluaciones.</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div
                            class="text-center py-8 border border-dashed border-gray-200 dark:border-gray-800 rounded-xl">
                            <i class="fas fa-folder-open text-gray-300 dark:text-gray-600 text-3xl mb-2"></i>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Sin módulos asignados</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL MÓDULO --}}
    <div x-show="openModuleModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openModuleModal" x-transition.opacity
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"
                @click="openModuleModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="openModuleModal" x-transition
                class="inline-block align-bottom bg-white dark:bg-gray-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-800">
                <form wire:submit="saveModule" class="p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $moduleId ? 'Editar Módulo' : 'Nuevo Módulo' }}</h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nombre del
                            Módulo</label>
                        <input type="text" wire:model="module_name"
                            class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        @error('module_name')
                            <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="openModuleModal = false"
                            class="px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl">Cancelar</button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL LECCIÓN CON QUILL --}}
    <div x-show="openLessonModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openLessonModal" x-transition.opacity
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"
                @click="openLessonModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="openLessonModal" x-transition
                class="inline-block align-bottom bg-white dark:bg-gray-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200 dark:border-gray-800">
                <form wire:submit="saveLesson" class="p-6 space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $lessonId ? 'Editar Lección' : 'Nueva Lección' }}</h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Título de la
                            Lección</label>
                        <input type="text" wire:model="lesson_title"
                            class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        @error('lesson_title')
                            <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">URL del Video
                            (Youtube, Vimeo, etc.)</label>
                        <input type="url" wire:model="lesson_video_url" placeholder="https://..."
                            class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        @error('lesson_video_url')
                            <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Editor Quill para Contenido --}}
                    <div x-data="{
                        content: @entangle('lesson_content'),
                        quill: null,
                        init() {
                            this.quill = new Quill($refs.editor, {
                                theme: 'snow',
                                placeholder: 'Escribe la teoría o inserta imágenes aquí...',
                                modules: {
                                    toolbar: [
                                        [{ 'header': [1, 2, 3, false] }],
                                        ['bold', 'italic', 'underline', 'strike'],
                                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                        ['blockquote', 'code-block'],
                                        ['link', 'image'],
                                        ['clean']
                                    ]
                                }
                            });
                    
                            if (this.content) {
                                this.quill.clipboard.dangerouslyPasteHTML(this.content);
                            }
                    
                            this.quill.on('text-change', () => {
                                this.content = this.quill.root.innerHTML;
                            });
                    
                            Livewire.on('load-lesson-content', (event) => {
                                const newContent = event.content || '';
                                if (this.quill.root.innerHTML !== newContent) {
                                    this.quill.root.innerHTML = newContent;
                                }
                            });
                        }
                    }" wire:ignore class="space-y-1">

                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Contenido /
                            Teoría (Con soporte para Imágenes)</label>

                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-300 dark:border-gray-700 overflow-hidden text-gray-900 dark:text-white">
                            <div x-ref="editor" class="min-h-[160px] text-sm"></div>
                        </div>

                        @error('lesson_content')
                            <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Documento
                            PDF</label>
                        @if ($existing_pdf)
                            <div
                                class="flex items-center justify-between p-2 mb-2 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 text-xs">
                                <span class="truncate max-w-[200px] text-gray-600 dark:text-gray-300"><i
                                        class="fas fa-file-pdf text-rose-500 mr-1"></i> PDF Adjunto</span>
                                <button type="button" wire:click="removePdf"
                                    class="text-rose-500 hover:underline">Eliminar</button>
                            </div>
                        @endif
                        <input type="file" wire:model="lesson_pdf" accept="application/pdf"
                            class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 dark:file:bg-indigo-950 dark:file:text-indigo-400">
                        @error('lesson_pdf')
                            <span class="text-xs text-rose-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="openLessonModal = false"
                            class="px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl">Cancelar</button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CDN Quill Script -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

</div>
