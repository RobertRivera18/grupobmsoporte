@props(['breadcrumbs' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}

    <!-- Fonts -->

    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://kit.fontawesome.com/f2ff89425f.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/02a894ba34.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @stack('css')
    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased sm:overflow-auto" :class="{ 'overflow-hidden': open }" x-data="{ open: false }">

    @include('layouts.includes.admin.nav')
    @include('layouts.includes.admin.aside')


    <div class="p-4 sm:ml-64">

        <div class="mt-14 -mb-10 flex justify-between">
            @include('layouts.includes.admin.breadcrumbs')

            @isset($action)
                {{ $action }}
            @endisset
        </div>

        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">

            {{ $slot }}
        </div>
    </div>


    <div x-cloak x-show = "open" x-on:click = "open = false"
        class="bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-30 sm:hidden">
    </div>
    @stack('modals')

    @livewireScripts

    @if (session('swal'))
        <script>
            Swal.fire(@json(session('swal')));
        </script>
    @endif


    @stack('js')
</body>

</html>
