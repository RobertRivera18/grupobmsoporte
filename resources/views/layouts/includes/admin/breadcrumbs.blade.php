@if ($breadcrumbs)
<nav aria-label="Breadcrumb" class="max-w-full overflow-hidden">
    <ol
        class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-gray-500 max-w-full">

        @foreach ($breadcrumbs as $item)

            {{-- PRIMER ELEMENTO (HOME) --}}
            @if ($loop->first)
                <li class="flex items-center max-w-full">
                    <a href="{{ $item['url'] }}"
                       class="inline-flex items-center gap-2 font-medium
                              text-gray-700 hover:text-blue-600
                              dark:text-gray-400 dark:hover:text-white
                              truncate max-w-[140px] sm:max-w-none">

                        <svg class="w-3 h-3 shrink-0" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>

                        <span class="truncate">
                            {{ $item['name'] }}
                        </span>
                    </a>
                </li>

            {{-- RESTO DE ELEMENTOS --}}
            @else
                <li class="flex items-center max-w-full">

                    {{-- Separador --}}
                    <svg class="w-3 h-3 text-gray-400 mx-1 shrink-0 rtl:rotate-180"
                         aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2" d="m1 9 4-4-4-4" />
                    </svg>

                    @isset($item['url'])
                        <a href="{{ $item['url'] }}"
                           class="font-medium text-gray-700 hover:text-blue-600
                                  dark:text-gray-400 dark:hover:text-white
                                  truncate max-w-[120px] sm:max-w-[200px] md:max-w-none">
                            {{ $item['name'] }}
                        </a>
                    @else
                        <span
                            class="font-medium text-gray-500 dark:text-gray-400
                                   truncate max-w-[120px] sm:max-w-[200px] md:max-w-none">
                            {{ $item['name'] }}
                        </span>
                    @endisset
                </li>
            @endif

        @endforeach
    </ol>
</nav>
@endif
