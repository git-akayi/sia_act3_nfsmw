<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-[#e32b2b] leading-tight uppercase tracking-[0.2em] italic">
                {{ __('IMPOUND LOT / BLACK MARKET') }}
            </h2>
            <div class="bg-black/40 border border-[#e32b2b]/30 px-4 py-2 rounded">
                <span class="text-gray-400 uppercase text-xs font-bold tracking-wider">Available Balance:</span>
                <span class="text-green-400 font-mono font-bold text-lg ml-2">${{ number_format(Auth::user()->bank_cash) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-900/40 border border-green-500 text-green-300 p-4 rounded mb-6 font-semibold uppercase tracking-wide text-xs">
                    🏁 {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-900/40 border border-[#e32b2b] text-red-300 p-4 rounded mb-6 font-semibold uppercase tracking-wide text-xs">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $imageMap = [
                        'Fiat Punto'                       => 'NFSMW_Fiat_GrandePunto_Stock_F.webp',
                        'Chevrolet Cobalt SS'              => 'NFSMW_Chevrolet_CobaltSS_Stock_F.webp',
                        'Volkswagen Golf GTI'              => 'NFSMW_Volkswagen_GolfGti_Stock_F.webp',
                        'Lexus IS300'                     => 'NFSMW_Lexus_IS300_Stock_F.webp',
                        'Audi TT 3.2 quattro'             => 'NFSMW_Audi_TTquattro_Stock_F.webp',
                        'Cadillac CTS'                    => 'NFSMW_Cadillac_CTS_Stock_F.webp',
                        'Mazda RX-8'                      => 'NFSMW_Mazda_RX8_Stock_F.webp',
                        'Audi A3 3.2 quattro'             => 'NFSMW_Audi_A3quattro_Stock_F.webp',
                        'Audi A4 3.2 FSI quattro'         => 'NFSMW_Audi_A4quattro_Stock_F.webp',
                        'Mitsubishi Eclipse'              => 'NFSMW_Mitsubishi_EclipseGT_Stock_F.webp',
                        'Mazda RX-7'                      => 'NFSMW_Mazda_RX7_Stock_F.webp',
                        'Renault Clio V6'                 => 'NFSMW_Renault_ClioV6_Stock_F.webp',
                        'Toyota Supra'                    => 'NFSMW_Toyota_Supra_Stock_F.webp',
                        'Mitsubishi Lancer Evolution VIII'=> 'NFSMW_Mitsubishi_LancerEvolutionVIII_Stock_F.webp',
                        'Subaru Impreza WRX STi'          => 'NFSMW_Subaru_ImprezaWRXSTi_Stock_F.webp',
                        'Ford Mustang GT'                 => 'NFSMW_Ford_MustangGT_Stock_F.webp',
                        'Pontiac GTO'                     => 'NFSMW_Pontiac_GTO_Stock_F.webp',
                        'Vauxhall Monaro VXR'             => 'NFSMW_Vauxhall_MonaroVXR_Stock_F.webp',
                        'Mercedes-Benz CLK 500'           => 'NFSMW_MercedesBenz_CLK500_Stock_F.webp',
                        'Mercedes-Benz SL 500'            => 'NFSMW_MercedesBenz_SL500_Stock_F.webp',
                        'Lotus Elise'                     => 'NFSMW_Lotus_Elise_Stock_F.webp',
                        'Porsche Cayman S'                => 'NFSMW_Porsche_CaymanS_Stock_F.webp',
                        'Porsche 911 Carrera S'           => 'NFSMW_Porsche_911CarreraS_Stock_F.webp',
                        'Chevrolet Corvette C6'           => 'NFSMW_Chevrolet_CorvetteC6_Stock_F.webp',
                        'Aston Martin DB9'                => 'NFSMW_AstonMartin_DB9_Stock_F.webp',
                        'Porsche 911 Turbo S'             => 'NFSMW_Porsche_911TurboS_Stock_F.webp',
                        'Porsche 911 GT2'                 => 'NFSMW_Porsche_911GT2_Stock_F.webp',
                        'Mercedes-Benz SL 65 AMG'         => 'NFSMW_MercedesBenz_SL65AMG_Stock_F.webp',
                        'Dodge Viper SRT10'               => 'NFSMW_Dodge_ViperSRT10_Stock_F.webp',
                        'Lamborghini Gallardo'            => 'NFSMW_Lamborghini_Gallardo_Stock_F.webp',
                        'Lamborghini Murciélago'          => 'NFSMW_Lamborghini_Murcielago_Stock_F.webp',
                        'Ford GT'                         => 'NFSMW_Ford_GT_Stock_F.webp',
                        'Porsche Carrera GT'              => 'NFSMW_Porsche_CarreraGT_Stock_F.webp',
                        'Mercedes-Benz SLR McLaren'       => 'NFSMW_MercedesBenz_SLRMcLaren_Stock_F.webp',
                        'Chevrolet Corvette C6.R'         => 'NFSMW_Chevrolet_CorvetteC6R_Stock_F.webp',
                        'BMW M3 GTR'                      => 'NFSMW_BMW_M3GTR_Stock_F.webp',
                    ];
                @endphp

                @forelse($availabeCars as $car)
                    @php
                        $imgFile = $imageMap[$car->make_model] ?? null;
                        $imgPath = $imgFile ? 'images/cars/' . $imgFile : null;
                    @endphp
                    <div class="bg-zinc-900 border-2 border-zinc-800 hover:border-[#e32b2b]/60 transition-all rounded-lg overflow-hidden flex flex-col justify-between shadow-xl">

                        {{-- Car Image --}}
                        <div class="bg-black w-full h-44 flex items-center justify-center overflow-hidden">
                            @if($imgPath)
                                <img src="{{ asset($imgPath) }}"
                                     alt="{{ $car->make_model }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-zinc-600 font-mono text-xs uppercase tracking-widest\'>No Image</span>'">
                            @else
                                <span class="text-zinc-600 font-mono text-xs uppercase tracking-widest">No Image</span>
                            @endif
                        </div>

                        <div class="p-6">
                            {{-- Car Name + Tier Badge --}}
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-black text-white uppercase tracking-wide italic">{{ $car->make_model }}</h3>
                                <span class="bg-zinc-800 text-zinc-400 text-xs px-2 py-1 rounded font-mono">
                                    {{ $car->tier ? 'TIER ' . $car->tier : 'STOCK' }}
                                </span>
                            </div>

                            {{-- Engine --}}
                            @if($car->engine_type)
                                <div class="mb-3 bg-zinc-800/50 px-3 py-2 border-l-2 border-[#e32b2b]">
                                    <span class="text-zinc-500 uppercase text-[9px] tracking-widest font-black">Engine</span>
                                    <p class="text-white font-black text-sm uppercase">{{ $car->engine_type }}</p>
                                </div>
                            @endif

                            {{-- Spec Grid --}}
                            <div class="grid grid-cols-2 gap-px bg-zinc-800 mt-3">
                                <div class="bg-zinc-900 p-3">
                                    <p class="text-zinc-500 uppercase text-[9px] tracking-widest mb-1">Power</p>
                                    <p class="text-white font-black text-sm">{{ $car->base_hp }} HP</p>
                                </div>
                                <div class="bg-zinc-900 p-3">
                                    <p class="text-zinc-500 uppercase text-[9px] tracking-widest mb-1">Torque</p>
                                    <p class="text-white font-black text-sm">{{ $car->base_torque }} lb-ft</p>
                                </div>
                                <div class="bg-zinc-900 p-3">
                                    <p class="text-zinc-500 uppercase text-[9px] tracking-widest mb-1">Top Speed</p>
                                    <p class="text-white font-black text-sm">{{ $car->top_speed ?? '—' }} mph</p>
                                </div>
                                <div class="bg-zinc-900 p-3">
                                    <p class="text-zinc-500 uppercase text-[9px] tracking-widest mb-1">Weight</p>
                                    <p class="text-white font-black text-sm">{{ $car->weight_kg ? number_format($car->weight_kg) . ' kg' : '—' }}</p>
                                </div>
                            </div>

                            {{-- Transmission --}}
                            @if($car->transmission)
                                <div class="mt-px bg-zinc-900 px-3 py-2 border-t border-zinc-800">
                                    <p class="text-zinc-500 uppercase text-[9px] tracking-widest mb-1">Transmission</p>
                                    <p class="text-white font-black text-sm">{{ $car->transmission }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Price + Buy --}}
                        <div class="p-6 bg-zinc-950 border-t border-zinc-800/50 flex items-center justify-between">
                            <span class="text-green-400 font-mono font-black text-xl">${{ number_format($car->base_market_value) }}</span>
                            <form action="{{ route('marketplace.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="car_id" value="{{ $car->id }}">
                                <button type="submit" class="bg-[#e32b2b] hover:bg-red-700 text-white font-black uppercase tracking-wider text-xs px-4 py-2.5 rounded italic transition-all transform active:scale-95 shadow-lg shadow-red-900/20">
                                    Buy
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-zinc-500 font-mono uppercase tracking-widest">
                        No vehicles available in the yard database. Run the catalog seeder!
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>