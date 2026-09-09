<div>
    {{-- Botón para abrir modal --}}
    <button wire:click="$set('open', true)" type="button"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-slate-900 hover:bg-slate-800 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-slate-400/20 transition-all shadow-xs">
        <i class="fas fa-star text-amber-400 text-xs"></i>
        Calificar indicador
    </button>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- ===================== --}}
        {{-- RESUMEN DE VALORACIONES --}}
        {{-- ===================== --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-6">

            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 text-center">
                Reseña del Indicador
            </h2>

            {{-- TOTAL --}}
            <div class="text-center space-y-1">
                <p class="text-4xl font-bold text-slate-900 tracking-tight">
                    {{ $anio->reviews->count() }}
                </p>

                <ul class="flex justify-center text-xs mt-1 gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <li class="mx-0.5">
                            <i class="fas fa-star text-amber-400 text-[10px]"></i>
                        </li>
                    @endfor
                </ul>

                <p class="text-xs text-slate-400">
                    Valoraciones totales
                </p>
            </div>

            {{-- DISTRIBUCIÓN --}}
            <ul class="space-y-2.5">
                @for ($i = 5; $i >= 1; $i--)
                    <li class="flex items-center gap-2 text-xs">

                        <span class="w-10 text-right font-medium text-slate-600">
                            {{ $i }}★
                        </span>

                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-400 transition-all rounded-full"
                                style="width: {{ $anio->reviews->count() > 0 ? ($ratingsCount[$i] * 100) / $anio->reviews->count() : 0 }}%">
                            </div>
                        </div>

                        <span class="w-10 text-slate-400 text-right font-medium">
                            {{ $anio->reviews->count() > 0 ? round(($ratingsCount[$i] * 100) / $anio->reviews->count()) : 0 }}%
                        </span>

                    </li>
                @endfor
            </ul>

        </div>

        {{-- ================= --}}
        {{-- LISTA DE RESEÑAS --}}
        {{-- ================= --}}
        <div x-data="{ open: false }" class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
            {{-- HEADER --}}
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 tracking-tight">
                    {{ $anio->reviews->count() }} valoraciones
                </h3>

                {{-- BOTÓN TOGGLE --}}
                <button @click="open = !open"
                    class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-all flex items-center gap-1.5 bg-slate-100 px-3 py-1.5 rounded-xl">
                    <span x-text="open ? 'Ocultar reseñas' : 'Ver reseñas'"></span>
                    <i class="fas fa-chevron-down transition-transform text-[10px]" :class="{ 'rotate-180': open }"></i>
                </button>
            </div>

            {{-- CONTENIDO --}}
            <div x-show="open" x-transition x-cloak class="mt-4">
                {{-- SI NO HAY RESEÑAS --}}
                @if ($anio->reviews->count() === 0)
                    <div class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mb-3 border border-slate-100">
                            <i class="far fa-comment-dots text-lg"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">
                            Aún no hay reseñas
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Sé el primero en dejar tu opinión
                        </p>
                    </div>
                @else
                    {{-- LISTADO --}}
                    <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1">

                        @foreach ($anio->reviews as $review)
                            <article
                                class="flex items-start gap-3.5 p-4 rounded-xl bg-slate-50/70 border border-slate-200/60 hover:bg-slate-50 transition-all">

                                {{-- AVATAR --}}
                                <img class="h-9 w-9 rounded-full object-cover ring-1 ring-slate-200/80 shrink-0"
                                    src="{{ $review->user->profile_photo_url }}" alt="{{ $review->user->name }}">

                                {{-- CONTENIDO --}}
                                <div class="flex-1 space-y-1">

                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-semibold text-slate-900">
                                            {{ $review->user->name }}
                                        </p>
                                        <span class="text-[11px] text-slate-400">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    {{-- ESTRELLAS --}}
                                    <ul class="flex text-[10px] gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li>
                                                <i class="fas fa-star {{ $review->rating >= $i ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                            </li>
                                        @endfor
                                    </ul>

                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                                        {{ $review->comment }}
                                    </p>
                                </div>

                                @can('delete', $review)
                                    <x-dropdown align="right" width="32">
                                        <x-slot name="trigger">
                                            <span class="cursor-pointer text-slate-400 hover:text-slate-600 text-base leading-none select-none transition">
                                                &middot;&middot;&middot;
                                            </span>
                                        </x-slot>

                                        <x-slot name="content">
                                            <x-dropdown-link wire:click="eliminar({{ $review->id }})"
                                                class="text-rose-600 hover:bg-rose-50 cursor-pointer text-xs">
                                                <i class="fas fa-trash-alt text-[10px] mr-2"></i>
                                                Eliminar
                                            </x-dropdown-link>
                                        </x-slot>
                                    </x-dropdown>
                                @endcan
                            </article>
                        @endforeach

                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- MODAL DE CALIFICACIÓN --}}
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <span wire:click="$set('open', false)" class="float-right cursor-pointer text-slate-400 hover:text-slate-600 transition">
                <i class="fa fa-times text-sm"></i>
            </span>
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col items-center w-full p-2 sm:p-4">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">¡Tu opinión importa!</h2>
                <p class="text-xs text-slate-500 mt-1">Califica tu experiencia con este indicador</p>

                {{-- Estrellas Interactivas --}}
                <ul class="flex my-5 gap-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <li wire:click="$set('rating', {{ $i }})" class="cursor-pointer transition hover:scale-110">
                            <i class="fas fa-star text-2xl {{ $rating >= $i ? 'text-amber-400' : 'text-slate-200' }}"></i>
                        </li>
                    @endfor
                </ul>

                @error('rating')
                    <span class="text-rose-500 text-xs font-medium">{{ $message }}</span>
                @enderror

                {{-- Comentario --}}
                <textarea wire:model.defer="comment" rows="3" 
                    class="w-full mt-4 p-3.5 bg-slate-50/70 border border-slate-200/80 rounded-xl resize-none text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-400/10 transition-all"
                    placeholder="Escribe tu opinión..."></textarea>

                @error('comment')
                    <span class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</span>
                @enderror

                <button wire:click="store"
                    class="w-full mt-6 bg-slate-900 hover:bg-slate-800 active:scale-[0.98] text-white text-sm font-semibold py-3 rounded-xl transition-all shadow-xs">
                    Enviar calificación
                </button>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('open', false)" class="border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl">
                Cancelar
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>