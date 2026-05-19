<x-guest-layout>
    <div class="max-w-2xl mx-auto bg-[#141414] border border-red-900/40 p-8 shadow-2xl relative">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-input-label for="name" value="{{ __('Driver Name') }}" class="text-gray-400 font-bold uppercase text-xs tracking-wider" />
                <x-text-input id="name" class="block mt-1 w-full bg-black border-gray-800 text-white focus:border-red-600 focus:ring-red-600 uppercase font-black italic" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" value="{{ __('Email Address') }}" class="text-gray-400 font-bold uppercase text-xs tracking-wider" />
                <x-text-input id="email" class="block mt-1 w-full bg-black border-gray-800 text-white focus:border-red-600 focus:ring-red-600" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" value="{{ __('Security Passkey') }}" class="text-gray-400 font-bold uppercase text-xs tracking-wider" />
                <x-text-input id="password" class="block mt-1 w-full bg-black border-gray-800 text-white focus:border-red-600 focus:ring-red-600" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" value="{{ __('Confirm Passkey') }}" class="text-gray-400 font-bold uppercase text-xs tracking-wider" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-black border-gray-800 text-white focus:border-red-600 focus:ring-red-600" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label value="SELECT YOUR OFFICER AVATAR" class="text-red-600 font-black text-xs tracking-[0.15em] mb-3 italic animate-pulse" />
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-black p-4 border border-gray-900 shadow-[inset_0_0_15px_rgba(0,0,0,0.8)] max-h-[420px] overflow-y-auto custom-scrollbar">
                    
                    @php
                        $drivers = [
                            'razor.png'   => 'Razor',
                            'bull.png'    => 'Bull',
                            'ronnie.png'  => 'Ronnie',
                            'baron.png'   => 'Baron',
                            'webster.png' => 'Webster',
                            'ming.png'    => 'Ming',
                            'kaze.png'    => 'Kaze',
                            'jewels.png'  => 'Jewels',
                            'earl.png'    => 'Earl',
                            'vic.png'     => 'Vic',
                            'jv.png'      => 'JV',
                            'izzy.png'    => 'Izzy',
                            'biglou.png'  => 'Big Lou',
                            'taz.png'     => 'Taz',
                            'sonny.png'   => 'Sonny'
                        ];
                    @endphp

                    @foreach($drivers as $filename => $displayname)
                    <label class="group cursor-pointer">
                        <input type="radio" name="avatar" value="{{ $filename }}" class="hidden peer">
                        <div class="relative overflow-hidden border border-gray-800 grayscale group-hover:grayscale-0 peer-checked:grayscale-0 peer-checked:border-red-600 transition-all duration-300">
                            <img src="{{ asset('images/avatars/' . $filename) }}" class="w-full h-24 object-cover" alt="{{ $displayname }}">
                            <div class="absolute bottom-0 w-full bg-black/80 text-[9px] text-center text-gray-400 font-black tracking-tighter py-0.5 group-hover:text-red-500 peer-checked:text-red-500 uppercase italic">
                                {{ $displayname }}
                            </div>
                        </div>
                    </label>
                    @endforeach

                    <label class="group cursor-pointer">
                        <input type="radio" name="avatar" value="nfsmw.jpg" class="hidden peer" checked>
                        <div class="relative overflow-hidden border border-gray-800 grayscale group-hover:grayscale-0 peer-checked:grayscale-0 peer-checked:border-red-600 transition-all duration-300">
                            <img src="{{ asset('images/avatars/nfsmw.jpg') }}" class="w-full h-24 object-cover" alt="Default">
                            <div class="absolute bottom-0 w-full bg-black/80 text-[9px] text-center text-gray-400 font-black tracking-tighter py-0.5 group-hover:text-red-500 peer-checked:text-red-500 uppercase italic">
                                Default
                            </div>
                        </div>
                    </label>

                </div>
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-900">
                <a class="underline text-xs text-gray-500 hover:text-gray-300 transition uppercase tracking-tighter font-bold italic" href="{{ route('login') }}">
                    {{ __('Abort to Login') }}
                </a>

                <x-primary-button class="ml-4 bg-red-700 hover:bg-red-600 text-white font-black italic tracking-widest px-6 rounded-none border border-red-500/30 transition shadow-[0_0_10px_rgba(185,28,28,0.3)]">
                    {{ __('Initialize Profile') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>