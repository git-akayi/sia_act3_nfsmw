<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-black text-2xl text-[#e32b2b] leading-tight uppercase tracking-[0.2em] italic">
                {{ __('Race Control') }}
            </h2>
            <div class="flex items-center gap-2">
                <div class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-600 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                </div>
                <span class="text-[9px] font-black text-red-600 uppercase tracking-widest italic hidden sm:block">Live Link Active</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- RACE RESULT BANNER --}}
            @if(session('race_result'))
                <div class="border-l-4 {{ session('race_result') === 'WIN' ? 'border-green-500 bg-green-900/20' : 'border-red-600 bg-red-900/20' }} p-6 shadow-2xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black mb-1">
                                Race Result — {{ session('race_type') }}
                            </p>
                            <h3 class="text-4xl font-black italic uppercase {{ session('race_result') === 'WIN' ? 'text-green-400' : 'text-red-500' }}">
                                {{ session('race_result') === 'WIN' ? '🏁 VICTORY' : '💀 DEFEATED' }}
                            </h3>
                            @if(session('ranked_up'))
                                <p class="text-yellow-400 font-black uppercase tracking-widest text-sm mt-2 italic">
                                    ⬆️ RANKED UP! #{{ session('old_rank') }} → #{{ session('new_rank') }}
                                </p>
                            @endif
                            @if(session('boss_rank'))
                                <p class="text-orange-500 font-black uppercase tracking-widest text-xs mt-1 italic">
                                    🏆 BLACKLIST #{{ session('boss_rank') }} BOSS {{ session('race_result') === 'WIN' ? 'DEFEATED' : 'SURVIVED' }}
                                </p>
                            @endif
                        </div>
                        <div class="grid grid-cols-4 gap-4 text-center font-mono">
                            <div class="bg-black/40 p-3 border border-gray-800">
                                <p class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Your Score</p>
                                <p class="text-lg font-black text-white">{{ session('performance') }}</p>
                            </div>
                            <div class="bg-black/40 p-3 border border-gray-800">
                                <p class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Opponent</p>
                                <p class="text-lg font-black text-red-500">{{ session('opponent') }}</p>
                            </div>
                            <div class="bg-black/40 p-3 border border-gray-800">
                                <p class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Cash Earned</p>
                                <p class="text-lg font-black text-orange-500">${{ number_format(session('cash_earned')) }}</p>
                            </div>
                            <div class="bg-black/40 p-3 border border-gray-800">
                                <p class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Bounty</p>
                                @php $bountyChange = session('bounty_change', 0); @endphp
                                <p class="text-lg font-black {{ $bountyChange >= 0 ? 'text-yellow-400' : 'text-red-400' }}">
                                    {{ $bountyChange >= 0 ? '+' : '' }}{{ number_format($bountyChange) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- BLACKLIST BOSS RACE --}}
            @if($blacklistRaceAvailable)
                <div class="bg-[#1a0000]/80 border border-red-600/40 border-l-4 border-l-red-600 p-6 shadow-2xl">
                    <div class="flex items-center gap-3 mb-4">
                        <h3 class="font-black text-xl text-red-500 uppercase tracking-wider italic">
                            ⚠️ BLACKLIST RACE AVAILABLE
                        </h3>
                        <div class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-600 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                        </div>
                    </div>

                    <p class="text-gray-400 text-xs uppercase tracking-widest font-mono mb-4">
                        BLACKLIST RANK #{{ $blacklistRaceAvailable }} BOSS IS WAITING.
                        DEFEAT THEM TO CLIMB THE RANKS.
                    </p>

                    <div class="bg-black/40 border border-red-600/20 p-4 mb-6">
                        <div class="grid grid-cols-3 gap-4 font-mono text-xs text-center">
                            <div class="bg-[#1a1a1a] px-4 py-2 border border-gray-800">
                                <span class="block text-gray-500 mb-1 uppercase tracking-widest text-[9px]">Target Rank</span>
                                <span class="text-red-500 font-black text-lg">#{{ $blacklistRaceAvailable }}</span>
                            </div>
                            <div class="bg-[#1a1a1a] px-4 py-2 border border-gray-800">
                                <span class="block text-gray-500 mb-1 uppercase tracking-widest text-[9px]">Boss Difficulty</span>
                                <span class="text-orange-500 font-black text-lg">
                                    {{ \App\Http\Controllers\RaceController::getBlacklistBossDifficulty()[$blacklistRaceAvailable] }}
                                </span>
                            </div>
                            <div class="bg-[#1a1a1a] px-4 py-2 border border-gray-800">
                                <span class="block text-gray-500 mb-1 uppercase tracking-widest text-[9px]">Your Score</span>
                                <span class="text-white font-black text-lg">
                                    {{ $myCar ? (int)($myCar->current_hp + ($myCar->current_torque * 0.5)) : '—' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('race.blacklist') }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-red-700 hover:bg-red-600 text-white font-black uppercase py-4 italic tracking-widest text-sm transition-all shadow-lg">
                            🏁 CHALLENGE BLACKLIST #{{ $blacklistRaceAvailable }} BOSS
                        </button>
                    </form>
                </div>
            @endif

            {{-- RACE NOW PANEL --}}
            <div class="bg-[#151515]/80 border-l-4 border-red-600 p-6 shadow-2xl">
                <h3 class="font-black text-xl text-[#e32b2b] uppercase tracking-wider italic mb-6">
                    // SELECT RACE EVENT
                </h3>

                @if($myCar)
                    {{-- Active Vehicle --}}
                    <div class="mb-6 bg-black/40 border border-gray-900 p-4">
                        <p class="text-[9px] text-gray-500 uppercase tracking-widest font-black mb-3">Active Vehicle</p>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <h4 class="text-lg font-black text-white uppercase italic">
                                {{ $myCar->baseCar->make_model }}
                            </h4>
                            <div class="flex gap-4 font-mono text-xs">
                                <div class="bg-[#1a1a1a] px-4 py-2 border border-gray-800 text-center">
                                    <span class="block text-gray-500 mb-1">HP</span>
                                    <span class="text-white font-bold">{{ $myCar->current_hp }}</span>
                                </div>
                                <div class="bg-[#1a1a1a] px-4 py-2 border border-gray-800 text-center">
                                    <span class="block text-gray-500 mb-1">TORQUE</span>
                                    <span class="text-white font-bold">{{ $myCar->current_torque }}</span>
                                </div>
                                <div class="bg-[#1a1a1a] px-4 py-2 border border-gray-800 text-center">
                                    <span class="block text-gray-500 mb-1">SCORE</span>
                                    <span class="text-orange-500 font-bold">
                                        {{ (int)($myCar->current_hp + ($myCar->current_torque * 0.5)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Race Type Selection --}}
                    <form method="POST" action="{{ route('race.run') }}">
                        @csrf
                        <p class="text-[9px] text-gray-500 uppercase tracking-widest font-black mb-3">Choose Race Type</p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                            @foreach([
                                ['name' => 'Sprint',    'emoji' => '🏃', 'desc' => 'Point to Point',  'bounty' => '1.2x'],
                                ['name' => 'Circuit',   'emoji' => '🔄', 'desc' => 'Lap Based',        'bounty' => '1.3x'],
                                ['name' => 'Drag',      'emoji' => '🚦', 'desc' => 'Quarter Mile',     'bounty' => '0.9x'],
                                ['name' => 'Drift',     'emoji' => '🌀', 'desc' => 'Style Points',     'bounty' => '1.5x'],
                                ['name' => 'Speedtrap', 'emoji' => '📡', 'desc' => 'Top Speed',        'bounty' => '1.3x'],
                                ['name' => 'Knockout',  'emoji' => '💥', 'desc' => 'Last Place Loses', 'bounty' => '1.5x'],
                                ['name' => 'Tollbooth', 'emoji' => '🛣️', 'desc' => 'Beat the Clock',  'bounty' => '1.1x'],
                            ] as $type)
                                <label class="cursor-pointer">
                                    <input type="radio" name="race_type" value="{{ $type['name'] }}"
                                        class="peer hidden" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="border border-gray-800 bg-black/40 p-4 text-center peer-checked:border-red-600 peer-checked:bg-red-900/20 transition-all hover:border-gray-600">
                                        <p class="text-lg font-black text-white uppercase italic tracking-wider">
                                            {{ $type['emoji'] }} {{ $type['name'] }}
                                        </p>
                                        <p class="text-[9px] text-gray-500 uppercase tracking-widest mt-1">
                                            {{ $type['desc'] }}
                                        </p>
                                        <p class="text-[8px] text-yellow-600 uppercase tracking-widest mt-1 font-black">
                                            ⚡ BOUNTY {{ $type['bounty'] }}
                                        </p>
                                        @if(strtolower(Auth::user()->race_specialty) === strtolower($type['name']))
                                            <span class="inline-block mt-2 text-[8px] bg-red-600 text-white px-2 py-0.5 uppercase tracking-widest font-black">
                                                YOUR SPECIALTY
                                            </span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-red-700 hover:bg-red-600 text-white font-black uppercase px-10 py-4 italic tracking-widest text-sm transition-all shadow-lg">
                                🏎️ RACE NOW
                            </button>
                        </div>
                    </form>

                @else
                    <div class="border border-dashed border-gray-800 p-8 text-center text-gray-500 font-mono uppercase tracking-widest bg-black/40">
                        No vehicle found. Head to the
                        <a href="{{ route('marketplace.index') }}"
                            class="text-[#e32b2b] underline font-bold hover:text-red-500 transition-colors">
                            Impound Lot
                        </a>
                        to acquire a ride first.
                    </div>
                @endif
            </div>

            {{-- RACE HISTORY --}}
            <div class="bg-[#151515]/80 border-l-4 border-gray-700 p-6 shadow-2xl">
                <h3 class="font-black text-xl text-white uppercase tracking-wider italic mb-6">
                    // RACE HISTORY
                </h3>

                @forelse($recentRaces as $race)
                    <div class="flex items-center justify-between border-b border-gray-900 py-3 font-mono text-xs">
                        <div class="flex items-center gap-4">
                            <span class="font-black uppercase {{ $race->result === 'WIN' ? 'text-green-400' : 'text-red-500' }}">
                                {{ $race->result }}
                            </span>
                            <span class="text-gray-400 uppercase">{{ $race->race_type }}</span>
                        </div>
                        <div class="flex items-center gap-6 text-gray-500">
                            <span>Score: <span class="text-white">{{ $race->performance_score }}</span></span>
                            <span>Opp: <span class="text-red-500">{{ $race->opponent_difficulty }}</span></span>
                            <span class="{{ $race->cash_earned > 0 ? 'text-orange-500' : 'text-gray-600' }} font-black">
                                {{ $race->cash_earned > 0 ? '+$' . number_format($race->cash_earned) : '—' }}
                            </span>
                            @if($race->bounty_change ?? null)
                                <span class="{{ $race->bounty_change > 0 ? 'text-yellow-500' : 'text-red-400' }} font-black">
                                    ⚡ {{ $race->bounty_change > 0 ? '+' : '' }}{{ number_format($race->bounty_change) }}
                                </span>
                            @endif
                            <span class="text-gray-700">{{ $race->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 font-mono uppercase tracking-widest text-xs text-center py-6">
                        No races logged yet. Hit the track.
                    </p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>