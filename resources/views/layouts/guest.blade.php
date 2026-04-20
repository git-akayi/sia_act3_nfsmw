<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Rockport PD: Auth</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" 
             style="background: radial-gradient(circle, #1a1a1a 0%, #000000 100%);">
            
            <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-[#0d0d0d] border-t-2 border-red-600 shadow-[0_0_50px_rgba(0,0,0,0.9)] overflow-hidden sm:rounded-sm">
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-[10px] text-gray-600 uppercase tracking-[0.4em]">
                System Status: Connected // RPD-9.52.21
            </p>
        </div>
    </body>
</html>