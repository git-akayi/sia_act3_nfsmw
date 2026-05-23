<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-black text-2xl text-[#e32b2b] leading-tight uppercase tracking-[0.2em] italic">
                {{ __('Driver Command Center') }}
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="flash-notification-box"></div>

            @php
            $imageMap = [
                'Fiat Punto'                       => 'NFSMW_Fiat_GrandePunto_Stock_F.webp',
                'Chevrolet Cobalt SS'              => 'NFSMW_Chevrolet_CobaltSS_Stock_F.webp',
                'Volkswagen Golf GTI'              => 'NFSMW_Volkswagen_GolfGti_Stock_F.webp',
                'Lexus IS300'                      => 'NFSMW_Lexus_IS300_Stock_F.webp',
                'Audi TT 3.2 quattro'              => 'NFSMW_Audi_TTquattro_Stock_F.webp',
                'Cadillac CTS'                     => 'NFSMW_Cadillac_CTS_Stock_F.webp',
                'Mazda RX-8'                       => 'NFSMW_Mazda_RX8_Stock_F.webp',
                'Audi A3 3.2 quattro'              => 'NFSMW_Audi_A3quartto_Stock_F.webp',
                'Audi A4 3.2 FSI quattro'          => 'NFSMW_Audi_A4quattro_Stock_F.webp',
                'Mitsubishi Eclipse'               => 'NFSMW_Mitsubishi_EclipseGT_Stock_F.webp',
                'Mazda RX-7'                       => 'NFSMW_Mazda_RX7_Stock_F.webp',
                'Renault Clio V6'                  => 'NFSMW_Renault_ClioV6_Stock_F.webp',
                'Toyota Supra'                     => 'NFSMW_Toyota_Supra_Stock_F.webp',
                'Mitsubishi Lancer Evolution VIII' => 'NFSMW_Mitsubishi_LancerEvolutionVIII_Stock_F.webp',
                'Subaru Impreza WRX STi'           => 'NFSMW_Subaru_ImprezaWRXSTi_Stock_F.webp',
                'Ford Mustang GT'                  => 'NFSMW_Ford_MustangGT_Stock_F.webp',
                'Pontiac GTO'                      => 'NFSMW_Pontiac_GTO_Stock_F.webp',
                'Vauxhall Monaro VXR'              => 'NFSMW_Vauxhall_MonaroVXR_Stock_F.webp',
                'Mercedes-Benz CLK 500'            => 'NFSMW_MercedesBenz_CLK500_Stock_F.webp',
                'Mercedes-Benz SL 500'             => 'NFSMW_MercedesBenz_SL500_Stock_F.webp',
                'Lotus Elise'                      => 'NFSMW_Lotus_Elise_Stock_F.webp',
                'Porsche Cayman S'                 => 'NFSMW_Porsche_CaymanS_Stock_F.webp',
                'Porsche 911 Carrera S'            => 'NFSMW_Porsche_911CarreraS_Stock_F.webp',
                'Chevrolet Corvette C6'            => 'NFSMW_Chevrolet_CorvetteC6_Stock_F.webp',
                'Aston Martin DB9'                 => 'NFSMW_AstonMartin_DB9_Stock_F.webp',
                'Porsche 911 Turbo S'              => 'NFSMW_Porsche_911TurboS_Stock_F.webp',
                'Porsche 911 GT2'                  => 'NFSMW_Porsche_911GT2_Stock_F.webp',
                'Mercedes-Benz SL 65 AMG'          => 'NFSMW_MercedesBenz_SL65AMG_Stock_F.webp',
                'Dodge Viper SRT10'                => 'NFSMW_Dodge_ViperSRT10_Stock_F.webp',
                'Lamborghini Gallardo'             => 'NFSMW_Lamborghini_Gallardo_Stock_F.webp',
                'Lamborghini Murciélago'           => 'NFSMW_Lamborghini_Murcielago_Stock_F.webp',
                'Ford GT'                          => 'NFSMW_Ford_GT_Stock_F.webp',
                'Porsche Carrera GT'               => 'NFSMW_Porsche_CarreraGT_Stock_F.webp',
                'Mercedes-Benz SLR McLaren'        => 'NFSMW_MercedesBenz_SLRMcLaren_Stock_F.webp',
                'Chevrolet Corvette C6.R'          => 'NFSMW_Chevrolet_CorvetteC6R_Stock_F.webp',
                'BMW M3 GTR'                       => 'NFSMW_BMW_M3GTR_Stock_F.webp',
            ];

            // Rank progress calculation
            $thresholds  = \App\Http\Controllers\RaceController::getRankThresholds();
            $currentRank = Auth::user()->blacklist_rank;
            $nextRank    = $currentRank - 1;
            $currentMin  = $thresholds[$currentRank] ?? 0;
            $nextMin     = $thresholds[$nextRank] ?? null;
            $userBounty  = Auth::user()->bounty;
            $progress    = $nextMin
                ? min(100, (int)(($userBounty - $currentMin) / ($nextMin - $currentMin) * 100))
                : 100;
            @endphp

            {{-- RANK UP FLASH --}}
            @if(session('ranked_up'))
                <div class="mb-6 border-l-4 border-yellow-400 bg-yellow-900/20 p-4 flex items-center gap-4 shadow-2xl">
                    <span class="text-2xl">⬆️</span>
                    <div>
                        <p class="text-yellow-400 font-black uppercase tracking-widest text-sm italic">BLACKLIST RANK UP!</p>
                        <p class="text-gray-400 font-mono text-xs uppercase tracking-wider mt-0.5">
                            Moved from <span class="text-white font-bold">#{{ session('old_rank') }}</span>
                            to <span class="text-yellow-400 font-bold">#{{ session('new_rank') }}</span>
                        </p>
                    </div>
                </div>
            @endif

            <div class="flex justify-end mb-6">
                <button onclick="toggleTunerPanel()"
                    class="bg-black hover:bg-[#1c1c1c] text-gray-400 hover:text-red-500 font-black text-[11px] uppercase tracking-wider py-2 px-4 border border-gray-800 transition-all flex items-center gap-2 italic select-none">
                    <span>// TOGGLE ECU TUNER</span>
                    <svg id="tuner-chevron" class="w-3 h-3 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            <div class="bg-[#151515]/80 border-l-4 border-red-600 p-6 shadow-2xl flex flex-col md:flex-row gap-6 items-center md:items-stretch">
                <div class="flex-shrink-0">
                    <div class="relative w-40 h-40 border-2 border-red-600 rounded-md overflow-hidden shadow-[0_0_15px_rgba(220,38,38,0.3)] bg-black">
                        <img class="w-full h-full object-cover"
                            src="{{ asset('images/avatars/' . (Auth::user()->avatar ?? 'nfsmw.jpg')) }}"
                            onerror="this.onerror=null; this.src='/images/avatars/nfsmw.jpg';"
                            alt="{{ Auth::user()->name }}">
                    </div>
                </div>

                <div class="flex-grow flex flex-col justify-between w-full">
                    <div class="border-b border-gray-900 pb-3 mb-4 flex flex-col sm:flex-row sm:items-baseline justify-between gap-1 text-center sm:text-left">
                        <h2 class="text-3xl font-black text-white uppercase tracking-tight italic">
                            ALIAS: <span class="text-red-600">{{ Auth::user()->name }}</span>
                        </h2>
                        <div class="text-xl font-black text-red-600 uppercase tracking-tight italic">
                            RANK <span class="text-white font-mono">#{{ Auth::user()->blacklist_rank }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div class="bg-[#222222]/40 p-3 border border-gray-900">
                            <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-1">Bounty</p>
                            <p class="text-xl font-black text-orange-500 font-mono tracking-tighter">${{ number_format(Auth::user()->bounty) }}</p>
                        </div>
                        <div class="bg-[#222222]/40 p-3 border border-gray-900">
                            <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-1">Ride</p>
                            <p class="text-sm font-black text-white truncate mt-1">{{ Auth::user()->signature_car }}</p>
                        </div>
                        <div class="bg-[#222222]/40 p-3 border border-gray-900">
                            <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-1">Area</p>
                            <p class="text-sm font-black text-red-600 truncate mt-1">{{ Auth::user()->territory }}</p>
                        </div>
                        <div class="bg-[#222222]/40 p-3 border border-gray-900">
                            <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-1">Specialty</p>
                            <p class="text-sm font-black text-white truncate mt-1">{{ Auth::user()->race_specialty }}</p>
                        </div>
                    </div>

                    {{-- Rank Progress Bar --}}
                    <div class="mt-4">
                        <div class="flex justify-between text-[9px] font-mono uppercase text-gray-500 mb-1">
                            <span>Rank #{{ $currentRank }}</span>
                            @if($nextMin)
                                <span>Next: Rank #{{ $nextRank }} @ ${{ number_format($nextMin) }} bounty</span>
                            @else
                                <span class="text-yellow-400 font-black">// #1 MOST WANTED — MAX RANK</span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-900 h-1.5 overflow-hidden">
                            <div class="h-full bg-[#e32b2b] transition-all duration-700"
                                 style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="flex justify-between text-[9px] font-mono text-gray-700 mt-0.5">
                            <span>${{ number_format($userBounty) }}</span>
                            <span>{{ $progress }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tuner-panel" class="max-h-0 overflow-hidden transition-all duration-500 mt-0 opacity-0">
                <div class="bg-[#181818] border border-gray-900 p-6 shadow-2xl mt-8">
                    <form method="POST" action="{{ route('profile.updateStats') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        @csrf @method('PATCH')

                        <div>
                            <label class="block text-[10px] text-gray-500 uppercase font-black tracking-wider mb-1">Ride</label>
                            <select name="signature_car" class="w-full bg-black border-gray-800 text-white p-2 text-sm focus:border-red-600 focus:ring-0">
                                @forelse($myGarageCars as $myCar)
                                <option value="{{ $myCar->baseCar->make_model }}"
                                    {{ Auth::user()->signature_car == $myCar->baseCar->make_model ? 'selected' : '' }}>
                                    {{ $myCar->baseCar->make_model }}
                                </option>
                                @empty
                                <option disabled>No cars in garage</option>
                                @endforelse
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] text-gray-500 uppercase font-black tracking-wider mb-1">Area</label>
                            <input type="text" name="territory" value="{{ Auth::user()->territory }}" class="w-full bg-black border-gray-800 text-red-500 p-2 text-sm focus:border-red-600 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-500 uppercase font-black tracking-wider mb-1">Specialty</label>
                            <select name="race_specialty" class="w-full bg-black border-gray-800 text-white p-2 text-sm focus:border-red-600 focus:ring-0">
                                @foreach(['Sprint', 'Circuit', 'Drag', 'Drift', 'Speedtrap', 'Knockout', 'Tollbooth'] as $type)
                                <option value="{{ $type }}" {{ Auth::user()->race_specialty == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="blacklist_rank" value="{{ Auth::user()->blacklist_rank }}">
                        <input type="hidden" name="bounty" value="{{ Auth::user()->bounty }}">
                        <input type="hidden" name="cars_owned" value="{{ Auth::user()->cars_owned }}">

                        <div class="md:col-span-3 flex justify-end mt-4">
                            <button type="submit" class="bg-red-700 text-white text-xs font-black uppercase px-6 py-3 italic hover:bg-red-600 transition tracking-wider">Commit Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="font-black text-xl text-white uppercase tracking-wider italic mb-4 text-[#e32b2b]">
                    🏎️ Your Personal Garage
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($myGarageCars as $myCar)
                    @php
                    $imgFile = $imageMap[$myCar->baseCar->make_model] ?? null;
                    $imgPath = $imgFile ? asset('images/cars/' . $imgFile) : null;
                    @endphp
                    <div class="bg-[#151515]/80 border border-gray-900 rounded-none relative overflow-hidden shadow-2xl flex flex-col">

                        {{-- Condition Badge --}}
                        <div class="absolute top-0 right-0 z-10 px-3 py-1 text-xs font-mono font-bold uppercase {{ $myCar->mechanical_efficiency > 80 ? 'bg-green-600 text-white' : 'bg-amber-600 text-black' }}">
                            Condition: {{ $myCar->mechanical_efficiency }}%
                        </div>

                        {{-- Car Image --}}
                        <div class="w-full h-40 bg-black overflow-hidden">
                            @if($imgPath)
                            <img src="{{ $imgPath }}"
                                alt="{{ $myCar->baseCar->make_model }}"
                                class="w-full h-full object-cover"
                                onerror="this.onerror=null; this.style.display='none'">
                            @endif
                        </div>

                        <div class="p-6">
                            <h4 class="text-lg font-bold text-white uppercase italic tracking-wide">
                                {{ $myCar->baseCar->make_model }}
                            </h4>
                            @if($myCar->baseCar->engine_type)
                            <div class="my-3 bg-black/60 px-3 py-2 border-l-2 border-[#e32b2b]">
                                <span class="text-gray-500 uppercase text-[9px] tracking-widest font-black">Engine</span>
                                <p class="text-white font-black text-sm uppercase">{{ $myCar->baseCar->engine_type }}</p>
                            </div>
                            @endif

                            <div class="grid grid-cols-2 gap-px bg-gray-900 my-4 font-mono text-xs">
                                <div class="bg-black p-2">
                                    <span class="block text-gray-500 mb-1">POWER</span>
                                    <span class="text-white font-bold text-sm">{{ $myCar->current_hp }} HP</span>
                                </div>
                                <div class="bg-black p-2">
                                    <span class="block text-gray-500 mb-1">TORQUE</span>
                                    <span class="text-white font-bold text-sm">{{ $myCar->current_torque }} lb-ft</span>
                                </div>
                                <div class="bg-black p-2">
                                    <span class="block text-gray-500 mb-1">TOP SPEED</span>
                                    <span class="text-white font-bold text-sm">{{ $myCar->baseCar->top_speed ?? '—' }} mph</span>
                                </div>
                                <div class="bg-black p-2">
                                    <span class="block text-gray-500 mb-1">VALUATION</span>
                                    <span class="text-green-500 font-bold text-sm">${{ number_format($myCar->calculated_valuation) }}</span>
                                </div>
                                <div class="bg-black p-2 col-span-2">
                                    <span class="block text-gray-500 mb-1">TRANSMISSION</span>
                                    <span class="text-white font-bold text-sm">{{ $myCar->baseCar->transmission ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="flex justify-end mt-2">
                                <a href="{{ route('tuning.show', $myCar->id) }}" class="block text-center bg-zinc-950 hover:bg-zinc-800 border border-gray-800 text-white font-mono uppercase text-xs px-4 py-2 italic tracking-wider transition-all mt-4">
                                    OPEN TUNE PROFILE
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 border border-dashed border-gray-800 p-8 text-center text-gray-500 font-mono uppercase tracking-widest bg-black/40">
                        Your garage is empty. Head down to the <a href="{{ route('marketplace.index') }}" class="text-[#e32b2b] underline font-bold hover:text-red-500 transition-colors">Impound Lot</a> to buy your first project car!
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTunerPanel() {
            const panel = document.getElementById('tuner-panel');
            const chevron = document.getElementById('tuner-chevron');
            if (panel.style.maxHeight === '0px' || !panel.style.maxHeight || panel.style.maxHeight === '0') {
                panel.style.maxHeight = panel.scrollHeight + "px";
                panel.style.opacity = "1";
                panel.style.marginTop = "2rem";
                chevron.classList.add('rotate-180');
            } else {
                panel.style.maxHeight = "0";
                panel.style.opacity = "0";
                panel.style.marginTop = "0";
                chevron.classList.remove('rotate-180');
            }
        }
    </script>
</x-app-layout>