<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rockport PD: Blacklist</title>
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Nunito', sans-serif; }
            .nfs-bg { background: radial-gradient(circle, #1a1a1a 0%, #000000 100%); }
        </style>
    </head>
    <body class="antialiased nfs-bg text-white min-h-screen flex flex-col justify-center items-center">
        
        @if (Route::has('login'))
            <div class="fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-red-500 uppercase font-bold tracking-widest hover:underline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 uppercase font-bold tracking-widest hover:text-white transition">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-400 uppercase font-bold tracking-widest hover:text-white transition">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="text-center px-4">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/nfs.png') }}" alt="NFS Most Wanted Logo" class="h-32 w-auto filter drop-shadow-[0_0_15px_rgba(255,0,0,0.5)]">
            </div>

            <div class="max-w-2xl mx-auto">
                <h1 class="text-4xl font-black text-red-600 uppercase tracking-tighter mb-4 italic">
                    Rockport Police Department: Blacklist Index
                </h1>
                <p class="text-gray-400 text-lg leading-relaxed mb-8">
                    Welcome to the central intelligence hub for the Rockport City Most Wanted list. This system is designed for high-ranking officers to track, categorize, and update files on the 15 most dangerous street racers in the tri-city area. 
                    <br><br>
                    <span class="text-orange-500 font-bold uppercase tracking-widest text-sm">Caution: Unauthorized access is a federal offense.</span>
                </p>

                <div class="flex justify-center space-x-4">
                    <a href="{{ route('login') }}" class="bg-red-600 hover:bg-red-700 text-white font-black py-3 px-8 rounded-sm uppercase tracking-widest transition transform hover:scale-105">
                        Access Database
                    </a>
                </div>
            </div>
        </div>

        <div class="fixed bottom-4 text-[10px] text-gray-600 uppercase tracking-[0.5em]">
            System Status: Connected // RPD-9.52.21
        </div>
    </body>
</html>