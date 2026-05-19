<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-[#e32b2b] leading-tight uppercase tracking-[0.2em] italic">
            {{ __('Rockport PD: Blacklist Database') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-black text-red-600 uppercase tracking-tighter italic drop-shadow-md">
                    LIVE TARGET LEADERBOARD
                </h1>
            </div>

            <div class="bg-[#141414] border border-gray-900 shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black border-b-2 border-red-600/30 text-gray-400 font-black text-xs uppercase tracking-widest italic">
                                <th class="p-4 text-center w-20">Rank</th>
                                <th class="p-4">Driver Details</th>
                                <th class="p-4 text-left">Signature Ride</th>
                                <th class="p-4 text-left text-red-500">Reppin'</th>
                                <th class="p-4 text-left text-blue-400">Specialty</th>
                                <th class="p-4 text-orange-500 font-mono">Bounty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-950 font-bold uppercase italic text-sm">
                            @forelse($rivals as $index => $rival)
                                <tr class="hover:bg-[#1c1c1c] transition-colors border-b border-gray-900/50 {{ Auth::id() === $rival->id ? 'bg-red-950/20 border-l-4 border-l-red-600' : '' }}">
                                    <td class="p-4 text-center font-black text-lg text-gray-400">#{{ $index + 1 }}</td>
                                    <td class="p-4 flex items-center gap-4">
                                        <img src="{{ asset('images/avatars/' . ($rival->avatar ?? 'nfsmw.jpg')) }}" 
                                             onerror="this.src='{{ asset('images/avatars/nfsmw.jpg') }}'"
                                             class="w-10 h-10 border border-gray-800 object-cover bg-black" alt="">
                                        <div>
                                            <div class="text-white font-black tracking-tight flex items-center gap-2">
                                                {{ $rival->name }}
                                                @if(Auth::id() === $rival->id)
                                                    <span class="text-[9px] bg-red-600 text-white px-1.5 py-0.5 font-mono font-black tracking-widest rounded-none">YOU</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-gray-400 tracking-tight font-black font-mono not-italic">
                                                BLACKLIST RANK: #{{ $rival->blacklist_rank ?? '15' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-left text-white font-black text-sm uppercase tracking-tight">
                                        {{ $rival->signature_car ?? 'BMW M3 GTR' }}
                                    </td>
                                    <td class="p-4 text-left text-red-500 font-bold uppercase text-sm">
                                        {{ $rival->territory ?? 'Rosewood' }}
                                    </td>
                                    <td class="p-4 text-left text-blue-400 font-bold uppercase text-sm">
                                        {{ $rival->race_specialty ?? 'Sprint' }}
                                    </td>
                                    <td class="p-4 text-orange-500 font-mono font-black tracking-tighter text-base">
                                        ${{ number_format($rival->bounty ?? 0) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-12 text-center text-gray-500 font-black tracking-widest text-xs uppercase animate-pulse">
                                        SCANNING FOR HIGH-HEAT TARGETS... NO RIVALS FOUND IN COMPILER.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-12 flex items-center justify-between opacity-30">
                <div class="h-[1px] bg-gray-700 w-1/4"></div>
                <p class="text-[9px] text-gray-500 font-mono uppercase tracking-[0.6em] px-4 text-center">
                    Rockport Internal Network Security Protocol v9.52.21
                </p>
                <div class="h-[1px] bg-gray-700 w-1/4"></div>
            </div>
        </div>
    </div>
</x-app-layout>