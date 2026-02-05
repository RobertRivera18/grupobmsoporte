<div>
    <button wire:click="$set('open', true)" type="button"
        class="
        inline-flex items-center gap-2
        px-4 py-2.5
        rounded-lg
        text-sm font-medium
        text-white
        bg-indigo-600
        hover:bg-indigo-700
        active:bg-indigo-800
        focus:outline-none
        focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2
        transition
        shadow-sm hover:shadow-md
    ">
        <i class="fas fa-star text-yellow-300"></i>
        Calificar indicador
    </button>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- ===================== --}}
        {{-- RESUMEN DE VALORACIONES --}}
        {{-- ===================== --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4">

            <h2 class="text-sm font-semibold text-gray-700 text-center">
                Reseña del Indicador
            </h2>

            {{-- TOTAL --}}
            <div class="text-center">
                <p class="text-4xl font-bold text-yellow-400">
                    {{ $anio->reviews->count() }}
                </p>

                <ul class="flex justify-center text-xs mt-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <li class="mx-0.5">
                            <i class="fas fa-star text-yellow-400"></i>
                        </li>
                    @endfor
                </ul>

                <p class="text-xs text-gray-500 mt-1">
                    Valoraciones totales
                </p>
            </div>

            {{-- DISTRIBUCIÓN --}}
            <ul class="space-y-2">
                @for ($i = 5; $i >= 1; $i--)
                    <li class="flex items-center gap-2 text-xs">

                        <span class="w-10 text-right text-gray-600">
                            {{ $i }}★
                        </span>

                        <div class="flex-1 h-2 bg-gray-200 rounded overflow-hidden">
                            <div class="h-full bg-yellow-400 transition-all"
                                style="width: {{ $anio->reviews->count() > 0 ? ($ratingsCount[$i] * 100) / $anio->reviews->count() : 0 }}%">
                            </div>
                        </div>

                        <span class="w-10 text-gray-500 text-right">
                            {{ $anio->reviews->count() > 0 ? round(($ratingsCount[$i] * 100) / $anio->reviews->count()) : 0 }}%
                        </span>

                    </li>
                @endfor
            </ul>

        </div>

        {{-- ================= --}}
        {{-- LISTA DE RESEÑAS --}}
        {{-- ================= --}}
        <div x-data="{ open: false }" class="lg:col-span-2  border-dashed border-gray-200 rounded-xl p-5">
            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">
                    {{ $anio->reviews->count() }} valoraciones
                </h3>

                {{-- BOTÓN TOGGLE --}}
                <button @click="open = !open"
                    class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                    <span x-text="open ? 'Ocultar reseñas' : 'Ver reseñas'"></span>
                    <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
            </div>

            {{-- CONTENIDO --}}
            <div x-show="open" x-transition x-cloak class="mt-3">
                {{-- SI NO HAY RESEÑAS --}}
                @if ($anio->reviews->count() === 0)
                    <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
                        <i class="far fa-comment-dots text-3xl mb-3"></i>
                        <p class="text-sm font-medium">
                            Aún no hay reseñas
                        </p>
                        <p class="text-xs">
                            Sé el primero en dejar tu opinión
                        </p>
                    </div>
                @else
                    {{-- LISTADO --}}
                    <div class="space-y-3 max-h-[420px] overflow-y-auto pr-1">

                        @foreach ($anio->reviews as $review)
                            <article
                                class="flex items-start gap-3 p-5 rounded-lg bg-gray-50 hover:bg-gray-100 transition">

                                {{-- AVATAR --}}
                                <img class="h-8 w-8 rounded-full object-cover ring-1 ring-gray-200"
                                    src="{{ $review->user->profile_photo_url }}" alt="{{ $review->user->name }}">

                                {{-- CONTENIDO --}}
                                <div class="flex-1 space-y-0.1">

                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-medium text-gray-800">
                                            {{ $review->user->name }}
                                        </p>
                                        <span class="text-[11px] text-gray-400">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    {{-- ESTRELLAS --}}
                                    <ul class="flex text-[11px]">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li class="mr-0.5">
                                                <i
                                                    class="fas fa-star {{ $review->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            </li>
                                        @endfor
                                    </ul>


                                    <p class="text-xs text-gray-600 leading-snug line-clamp-3">
                                        {{ $review->comment }}
                                    </p>
                                </div>



                                @can('delete', $review)
                                    <x-dropdown align="right" width="32">
                                        <x-slot name="trigger">
                                            <span
                                                class="cursor-pointer text-gray-400 hover:text-gray-600
                   text-lg leading-none select-none transition">
                                                &middot;&middot;&middot;
                                            </span>
                                        </x-slot>

                                        <x-slot name="content">
                                            <x-dropdown-link wire:click="eliminar({{ $review->id }})"
                                                class="text-red-600 hover:bg-red-50 cursor-pointer">
                                                <i class="fas fa-trash-alt text-xs mr-2"></i>
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


    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <span wire:click="$set('open', false)" class="float-right cursor-pointer">
                <i class="fa fa-times"></i>
            </span>
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col items-center w-full p-6">
                <h2 class="text-2xl font-semibold">¡Tu opinión importa!</h2>

                {{-- Estrellas --}}
                <ul class="flex my-4">
                    @for ($i = 1; $i <= 5; $i++)
                        <li wire:click="$set('rating', {{ $i }})" class="cursor-pointer mr-1">
                            <i
                                class="fas fa-star text-3xl {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        </li>
                    @endfor
                </ul>

                @error('rating')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                {{-- Comentario --}}
                <textarea wire:model.defer="comment" rows="3" class="w-full mt-4 p-3 border rounded-lg resize-none"
                    placeholder="Escribe tu opinión..."></textarea>

                @error('comment')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <button wire:click="store"
                    class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg">
                    Enviar calificación
                </button>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-danger-button wire:click="$set('open', false)">
                Cancelar
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
