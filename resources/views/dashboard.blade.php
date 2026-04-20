<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-[#e32b2b] leading-tight uppercase tracking-[0.2em] italic drop-shadow-[2px_2px_0px_rgba(0,0,0,0.8)]">
                {{ __('Driver Command Center') }}
            </h2>
            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest animate-pulse italic">
                // System Active
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <h1 class="text-4xl font-black text-red-600 uppercase tracking-tighter mb-8 italic drop-shadow-md">
                RAP SHEET: {{ Auth::user()->name }}
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-[#222222] border-l-4 border-red-600 p-6 shadow-[5px_5px_15px_rgba(0,0,0,0.5)] transform hover:translate-y-[-4px] transition-all">
                    <p class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest mb-1">Blacklist Rank</p>
                    <p class="text-4xl font-black text-white">#{{ Auth::user()->blacklist_rank ?? '15' }}</p>
                </div>

                <div class="bg-[#222222] border-l-4 border-red-600 p-6 shadow-[5px_5px_15px_rgba(0,0,0,0.5)] transform hover:translate-y-[-4px] transition-all">
                    <p class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest mb-1">Current Bounty</p>
                    <p class="text-4xl font-black text-orange-500 font-mono tracking-tighter">
                        ${{ number_format(Auth::user()->bounty ?? 0) }}
                    </p>
                </div>

                <div class="bg-[#222222] border-l-4 border-red-600 p-6 shadow-[5px_5px_15px_rgba(0,0,0,0.5)] transform hover:translate-y-[-4px] transition-all">
                    <p class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest mb-1">Cars Owned</p>
                    <p class="text-4xl font-black text-white">{{ Auth::user()->cars_owned ?? '1' }}</p>
                </div>

                <div class="bg-[#222222] border-l-4 border-red-600 p-6 shadow-[5px_5px_15px_rgba(0,0,0,0.5)] transform hover:translate-y-[-4px] transition-all">
                    <p class="text-[10px] text-gray-400 uppercase font-extrabold tracking-widest mb-1">Rivals Left</p>
                    <p class="text-4xl font-black text-red-600">{{ Auth::user()->rivals_left ?? '15' }}</p>
                </div>
            </div>

            <div class="mt-20 flex items-center justify-between opacity-40">
                <div class="h-[1px] bg-gray-600 w-1/4"></div>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.8em] px-4 text-center">
                    Rockport Police Department Central Database
                </p>
                <div class="h-[1px] bg-gray-600 w-1/4"></div>
            </div>
        </div>
    </div>
</x-app-layout>