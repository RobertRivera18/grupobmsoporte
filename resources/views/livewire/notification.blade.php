<div class="ms-3 relative">
    <x-dropdown align="right" width="64">
        <x-slot name="trigger">
            <span class="inline-flex rounded-md items-center">
                <button type="button" wire:click="resetNotification()" 
                    class=" flex p-2 rounded-full bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <!-- Icono de campana -->
                    <svg class="w-6 h-6 text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>

                    <!-- Badge contador -->
                  @if (auth()->user()->notification)
                        <span
                            class="px-2.5 py-0.5 text-sm font-medium text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">
                            {{ auth()->user()->notification }}
                        </span>
                    @endif
                </button>
            </span>


        </x-slot>

        <x-slot name="content">
            <div class="max-h-[calc(100vh-8rem)] overflow-auto">

                @if ($this->notifications->count())
                    <ul class="divide-y">
                        @foreach ($this->notifications as $notification)
                            <li @class([
                                'cursor-pointer transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 px-4 py-1 rounded-lg',
                                'bg-gray-200 dark:bg-gray-800 overflow-hidden' => !$notification->read_at,
                            ]) wire:click="readNotification('{{ $notification->id }}')">
                                <x-dropdown-link href="{{ $notification->data['ruta'] }}">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs font-medium text-gray-800 dark:text-gray-100">
                                            {{ $notification->data['message'] }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </x-dropdown-link>
                            </li>
                        @endforeach
                    @else
                        <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                            No tienes notificaciones
                        </div>
                @endif
                </ul>
                @if (auth()->user()->notifications->count() > $count)
                    <div class="px-4 pt-2 pb-1 flex justify-center ">
                        <button wire:click="incrementCount()" class="text-sm text-blue-500 font-semibold">Ver mas
                            notificaciones</button>
                    </div>
                @endif
            </div>
        </x-slot>
    </x-dropdown>
</div>
