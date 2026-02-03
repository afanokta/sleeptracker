<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="min-h-screen flex flex-col">
            {{ $slot }}
        </div>
        @persist('toast')
            <x-toast />
        @endpersist
        @fluxScripts
    </body>
</html>

