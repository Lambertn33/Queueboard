<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'QueueBoard') }}</title>

        @fonts

        @vite('resources/js/app.js')
        <x-inertia::head />
    </head>
    <body class="min-h-screen bg-[#F4F7FE] font-sans text-gray-900 antialiased">
        <x-inertia::app />
    </body>
</html>
