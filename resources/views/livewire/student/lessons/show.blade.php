<div x-data="{ sidebarOpen: true }" class="min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-white flex flex-col">

    {{-- Topbar Superior con Barra de Progreso General --}}
    <header class="h-14 bg-white dark:bg-gray-900 border-b border-gray-200/80 dark:border-gray-800 px-4 flex items-center justify-between z-20 shadow-sm gap-4">
        <div class="flex items-center gap-3 truncate">
            <a href="{{ route('student.courses.show', $course->id) }}"
                class="p-2 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                title="Volver al curso">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div class="truncate">
                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 block">{{ $course->name }}</span>
                <h1 class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">{{ $lesson->title }}</h1>
            </div>
        </div>

        {{-- Barra de Avance Global del Curso --}}
        <div class="hidden md:flex items-center gap-3 max-w-xs w-full bg-gray-50 dark:bg-gray-800/50 p-2 rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="flex-1 bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                <div class="bg-indigo-600 h-full rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
            </div>
            <span class="text-[11px] font-bold text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $progressPercentage }}%</span>
        </div>

        <button @click="sidebarOpen = !sidebarOpen"
            class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl transition-colors flex items-center gap-2">
            <i class="fas text-xs" :class="sidebarOpen ? 'fa-indent' : 'fa-outdent'"></i>
            <span class="hidden sm:inline" x-text="sidebarOpen ? 'Ocultar Temario' : 'Ver Temario'"></span>
        </button>
    </header>

    {{-- Área Principal --}}
    <div class="flex-1 flex overflow-hidden relative">

        {{-- Contenido de la Lección --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-8 space-y-6" wire:loading.class="opacity-50">

            @if ($lesson->video_url)
                @php
                    $rawUrl = trim($lesson->video_url);
                    $embedUrl = null;

                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $rawUrl, $matches)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                    } elseif (preg_match('/vimeo\.com\/(?:video\/)?([0-9]+)/', $rawUrl, $matches)) {
                        $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
                    }
                @endphp

                <div class="w-full bg-black rounded-2xl overflow-hidden aspect-video shadow-md border border-gray-200 dark:border-gray-800 relative">
                    @if ($embedUrl)
                        <iframe key="iframe-{{ $lesson->id }}" src="{{ $embedUrl }}" class="w-full h-full" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    @else
                        <video key="video-{{ $lesson->id }}" controls class="w-full h-full">
                            <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
                            Tu navegador no soporta reproducción de video.
                        </video>
                    @endif
                </div>
            @endif

            {{-- Controles de Navegación y Botón Toggle para Finalizar --}}
            <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-b border-gray-200/80 dark:border-gray-800 pb-6">
                <div>
                    @if ($previousLesson)
                        <button wire:click="selectLesson({{ $previousLesson->id }})"
                            class="px-4 py-2.5 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 border border-gray-200/80 dark:border-gray-800 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-2 shadow-sm">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                            <span>Anterior: {{ Str::limit($previousLesson->title, 20) }}</span>
                        </button>
                    @endif
                </div>

                {{-- Toggle de Lección Culminada --}}
                <div>
                    <button wire:click="toggleLessonCompletion"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2 border {{ $isCompleted ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/20' : 'bg-indigo-600 hover:bg-indigo-700 border-indigo-600 text-white' }}">
                        <i class="fas {{ $isCompleted ? 'fa-check-circle' : 'fa-circle' }} text-sm"></i>
                        <span>{{ $isCompleted ? 'Lección Completada' : 'Marcar como Completada' }}</span>
                    </button>
                </div>

                <div>
                    @if ($nextLesson)
                        <button wire:click="selectLesson({{ $nextLesson->id }})"
                            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors inline-flex items-center gap-2 shadow-sm">
                            <span>Siguiente: {{ Str::limit($nextLesson->title, 20) }}</span>
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Contenido de Texto Enriquecido Quill --}}
            <div class="space-y-6 max-w-4xl bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200/80 dark:border-gray-800 shadow-sm">
                <h2 class="text-xl font-black text-gray-900 dark:text-white">{{ $lesson->title }}</h2>

                @if ($lesson->content)
                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300 leading-relaxed dark:prose-invert prose-headings:font-bold prose-a:text-indigo-600 dark:prose-a:text-indigo-400">
                        {!! $lesson->content !!}
                    </div>
                @endif

                {{-- Material PDF Adjunto --}}
                @if ($lesson->pdf_path)
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-800 space-y-3">
                        <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Material de Lectura / Recurso PDF</h3>
                        <a href="{{ asset('storage/' . $lesson->pdf_path) }}" target="_blank" download
                            class="p-4 bg-gray-50 dark:bg-gray-800/60 hover:bg-gray-100 dark:hover:bg-gray-800 border border-gray-200/80 dark:border-gray-700/60 rounded-xl transition-colors inline-flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-lg bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 flex items-center justify-center font-bold">
                                <i class="fas fa-file-pdf text-lg"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200 block group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Descargar Documento PDF</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400">Clic para abrir o descargar el recurso</span>
                            </div>
                        </a>
                    </div>
                @endif
            </div>

        </main>

        {{-- Sidebar del Temario --}}
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="w-80 bg-white dark:bg-gray-900 border-l border-gray-200/80 dark:border-gray-800 flex-shrink-0 flex flex-col h-full z-10 shadow-sm">

            <div class="p-4 border-b border-gray-200/80 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Contenido del Curso</h3>
                <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 px-2 py-0.5 rounded-md border border-indigo-200 dark:border-indigo-800">
                    {{ $completedCount }}/{{ $totalLessons }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($course->modules as $mIndex => $module)
                    <div x-data="{ modOpen: true }" class="bg-white dark:bg-gray-900">
                        <button @click="modOpen = !modOpen"
                            class="w-full px-4 py-3 bg-gray-50/80 dark:bg-gray-800/40 hover:bg-gray-100 dark:hover:bg-gray-800 text-left flex items-center justify-between text-xs font-bold text-gray-800 dark:text-gray-200 border-b border-gray-100 dark:border-gray-800/60">
                            <span class="truncate">Módulo {{ $mIndex + 1 }}: {{ $module->name }}</span>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform"
                                :class="{ 'rotate-180': modOpen }"></i>
                        </button>

                        <div x-show="modOpen" class="divide-y divide-gray-50 dark:divide-gray-800/40">
                            @foreach ($module->lessons as $l)
                                @php 
                                    $isActive = $l->id === $activeLessonId; 
                                    $isLessonDone = in_array($l->id, $completedLessonIds);
                                @endphp
                                <button wire:click="selectLesson({{ $l->id }})"
                                    class="w-full px-4 py-3 flex items-center justify-between text-xs transition-colors border-l-2 text-left {{ $isActive ? 'bg-indigo-50/70 dark:bg-indigo-950/40 border-indigo-600 dark:border-indigo-500 text-indigo-600 dark:text-indigo-400 font-bold' : 'border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/40 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                    <div class="flex items-center gap-2.5 truncate">
                                        @if ($isLessonDone)
                                            <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                                        @elseif ($l->video_url)
                                            <i class="far {{ $isActive ? 'fa-play-circle text-indigo-600 dark:text-indigo-400' : 'fa-circle text-gray-400 dark:text-gray-600' }} text-[11px]"></i>
                                        @else
                                            <i class="far {{ $isActive ? 'fa-file-alt text-indigo-600 dark:text-indigo-400' : 'fa-file-alt text-gray-400 dark:text-gray-600' }} text-[11px]"></i>
                                        @endif
                                        <span class="truncate {{ $isLessonDone && !$isActive ? 'line-through text-gray-400 dark:text-gray-500' : '' }}">{{ $l->title }}</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </aside>

    </div>

</div>