<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-[#e32b2b] leading-tight uppercase tracking-[0.2em] italic">
                {{ __('TUNING WORKSHOP / DETAIL EDITOR') }}
            </h2>
            <div class="bg-black/40 border border-[#e32b2b]/30 px-4 py-2 rounded">
                <span class="text-gray-400 uppercase text-xs font-bold tracking-wider">BALANCE:</span>
                <span class="text-green-400 font-mono font-bold text-lg ml-2">${{ number_format(Auth::user()->cash) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-900/40 border border-green-500 text-green-300 p-4 rounded mb-6 font-semibold uppercase tracking-wide text-xs font-mono">
                    ⚙️ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-900/40 border border-[#e32b2b] text-red-300 p-4 rounded mb-6 font-semibold uppercase tracking-wide text-xs font-mono">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @php
                $imageMap = [
                    'Fiat Punto'                       => 'NFSMW_Fiat_GrandePunto_Stock_F.webp',
                    'Chevrolet Cobalt SS'              => 'NFSMW_Chevrolet_CobaltSS_Stock_F.webp',
                    'Volkswagen Golf GTI'              => 'NFSMW_Volkswagen_GolfGti_Stock_F.webp',
                    'Lexus IS300'                      => 'NFSMW_Lexus_IS300_Stock_F.webp',
                    'Audi TT 3.2 quattro'              => 'NFSMW_Audi_TTquattro_Stock_F.webp',
                    'Cadillac CTS'                     => 'NFSMW_Cadillac_CTS_Stock_F.webp',
                    'Mazda RX-8'                       => 'NFSMW_Mazda_RX8_Stock_F.webp',
                    'Audi A3 3.2 quattro'              => 'NFSMW_Audi_A3quattro_Stock_F.webp',
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
                $imgFile = $imageMap[$car->baseCar->make_model] ?? null;
                $imgPath = $imgFile ? asset('images/cars/' . $imgFile) : null;
            @endphp

            <div class="bg-zinc-900 border-2 border-zinc-800 rounded-lg overflow-hidden shadow-2xl">

                {{-- Car Image Banner --}}
                <div class="w-full h-56 bg-black overflow-hidden">
                    @if($imgPath)
                        <img src="{{ $imgPath }}"
                             alt="{{ $car->baseCar->make_model }}"
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.style.display='none'">
                    @endif
                </div>

                <div class="p-8">
                    {{-- Header --}}
                    <div class="flex justify-between items-start border-b border-zinc-800 pb-6 mb-6">
                        <div>
                            <span class="text-xs font-bold text-[#e32b2b] tracking-widest uppercase font-mono">// WORKBENCH PROJECT</span>
                            <h3 class="text-3xl font-black text-white uppercase tracking-wide italic mt-1">{{ $car->baseCar->make_model }}</h3>
                            @if($car->baseCar->engine_type)
                                <p class="text-zinc-500 font-mono text-xs uppercase tracking-widest mt-1">{{ $car->baseCar->engine_type }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="text-zinc-500 text-xs font-mono block uppercase">CURRENT ASSET VALUE</span>
                            <span class="text-green-400 font-mono font-black text-2xl">${{ number_format($car->calculated_valuation) }}</span>
                        </div>
                    </div>

                    {{-- Live Stats --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 font-mono">
                        <div class="bg-zinc-950 border border-zinc-800 p-4 rounded text-center">
                            <div class="text-zinc-500 text-xs uppercase mb-1">MEASURED POWER</div>
                            <div class="text-white text-xl font-bold">{{ $car->current_hp }} / <span class="text-zinc-600">{{ $car->baseCar->base_hp }} HP</span></div>
                        </div>
                        <div class="bg-zinc-950 border border-zinc-800 p-4 rounded text-center">
                            <div class="text-zinc-500 text-xs uppercase mb-1">MEASURED TORQUE</div>
                            <div class="text-white text-xl font-bold">{{ $car->current_torque }} / <span class="text-zinc-600">{{ $car->baseCar->base_torque }} LB-FT</span></div>
                        </div>
                        <div class="bg-zinc-950 border border-zinc-800 p-4 rounded text-center">
                            <div class="text-zinc-500 text-xs uppercase mb-1">ECU EFFICIENCY</div>
                            <div class="text-orange-500 text-xl font-bold">{{ $car->mechanical_efficiency }}%</div>
                        </div>
                    </div>

                    {{-- Base Spec Grid --}}
                    <div class="mb-6">
                        <p class="text-[9px] text-zinc-600 uppercase tracking-widest font-black mb-2">// BASE SPECIFICATIONS</p>
                        <div class="grid grid-cols-2 gap-px bg-zinc-800">
                            <div class="bg-zinc-900 p-3">
                                <p class="text-zinc-500 uppercase text-[9px] tracking-widest mb-1">Top Speed</p>
                                <p class="text-white font-black text-sm">{{ $car->baseCar->top_speed ?? '—' }} mph</p>
                            </div>
                            <div class="bg-zinc-900 p-3">
                                <p class="text-zinc-500 uppercase text-[9px] tracking-widest mb-1">Weight</p>
                                <p class="text-white font-black text-sm">{{ $car->baseCar->weight_kg ? number_format($car->baseCar->weight_kg) . ' kg' : '—' }}</p>
                            </div>
                            <div class="bg-zinc-900 p-3 col-span-2">
                                <p class="text-zinc-500 uppercase text-[9px] tracking-widest mb-1">Transmission</p>
                                <p class="text-white font-black text-sm">{{ $car->baseCar->transmission ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tune Action --}}
                    <div class="bg-black/30 border border-zinc-800 rounded p-6 flex flex-col sm:flex-row items-center justify-between">
                        <div class="mb-4 sm:mb-0">
                            <h4 class="text-white font-bold uppercase tracking-wide text-sm font-mono">// OPTIMIZE HARDWARE & CALIBRATE ECU</h4>
                            <p class="text-zinc-400 text-xs font-mono mt-1 uppercase">COST: <span class="text-green-400">$1,500</span> | RESTORES +10% MECHANICAL INFRASTRUCTURE</p>
                        </div>
                        <form action="{{ route('tuning.upgrade', $car->id) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-[#e32b2b] hover:bg-red-700 text-white font-black uppercase tracking-widest text-xs px-6 py-3 rounded italic transition-all shadow-lg">
                                EXECUTE TUNE STAGE
                            </button>
                        </form>
                    </div>

                    {{-- Sell Action --}}
                    <div class="bg-black/30 border border-zinc-800 rounded p-6 mt-4 flex flex-col sm:flex-row items-center justify-between">
                        <div class="mb-4 sm:mb-0">
                            <h4 class="text-green-400 font-bold uppercase tracking-wide text-sm font-mono">// SIMULATED BLACK-MARKET BOARD</h4>
                            <p class="text-zinc-400 text-xs font-mono mt-1 uppercase">
                                List this ride for a virtual offer. High ECU efficiency attracts premium buyer stakes.
                            </p>
                        </div>
                        <form action="{{ route('tuning.sell', $car->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to flip this project car? It will be permanently transferred out of your garage collection!');"
                              class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-600 text-white font-black uppercase tracking-widest text-xs px-6 py-3 rounded italic transition-all shadow-lg">
                                FLIP VEHICLE FOR CASH
                            </button>
                        </form>
                    </div>

                    {{-- Back Link --}}
                    <div class="mt-8 pt-4 border-t border-zinc-800/60 flex justify-between">
                        <a href="{{ route('dashboard') }}" class="text-zinc-500 hover:text-white font-mono text-xs uppercase tracking-wider transition-all">
                            ← RETURN TO COMMAND DASHBOARD
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>