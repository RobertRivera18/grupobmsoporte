@if (!empty($breadcrumbs))
<nav aria-label="Breadcrumb" class="max-w-full overflow-hidden">
    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">

        @foreach ($breadcrumbs as $item)
            <li class="flex items-center max-w-full">
                
                {{-- Separador (no se muestra en el primer elemento) --}}
                @if (!$loop->first)
                    <svg class="w-3 h-3 text-gray-400 mx-1.5 shrink-0 rtl:rotate-180"
                         aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg"
                         fill="none" 
                         viewBox="0 0 6 10">
                        <path stroke="currentColor" 
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8" 
                              d="m1 9 4-4-4-4" />
                    </svg>
                @endif

                {{-- ÍTEM INTERMEDIO O INICIAL CON ENLACE --}}
                @if (isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}"
                       class="inline-flex items-center gap-1.5 text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors truncate max-w-[140px] sm:max-w-none">
                        
                        @if ($loop->first)
                            {{-- Icono de Inicio por defecto --}}
                            <svg class="w-3.5 h-3.5 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-indigo-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                        @elseif(isset($item['icon']))
                            <i class="{{ $item['icon'] }} text-xs shrink-0 text-gray-400"></i>
                        @endif

                        <span class="truncate">{{ $item['name'] }}</span>
                    </a>

                {{-- ÚLTIMO ÍTEM (PÁGINA ACTUAL) --}}
                @else
                    <span class="inline-flex items-center gap-1.5 font-semibold text-gray-900 dark:text-white truncate max-w-[160px] sm:max-w-[260px] md:max-w-none" 
                          aria-current="page">
                        
                        @if ($loop->first)
                            <svg class="w-3.5 h-3.5 shrink-0 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                        @elseif(isset($item['icon']))
                            <i class="{{ $item['icon'] }} text-xs shrink-0"></i>
                        @endif

                        <span class="truncate">{{ $item['name'] }}</span>
                    </span>
                @endif

            </li>
        @endforeach

    </ol>
</nav>
@endif