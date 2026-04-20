<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/nfs.png') }}" 
             alt="RPD Logo" 
             class="h-20 w-auto filter drop-shadow-[0_0_10px_rgba(255,0,0,0.4)]">
    </div>

    <h2 class="text-xl font-black text-red-600 uppercase tracking-tighter mb-8 text-center italic">
        Authorized Personnel Only
    </h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-6">
            <label for="email" class="text-[10px] font-extrabold text-gray-500 uppercase tracking-[0.2em] mb-2 block">
                Officer ID (Email)
            </label>
            <input id="email" class="block mt-1 w-full bg-black border border-gray-800 text-white focus:border-red-600 focus:ring-0 rounded-none transition py-3 px-4" 
                   type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-6">
            <label for="password" class="text-[10px] font-extrabold text-gray-500 uppercase tracking-[0.2em] mb-2 block">
                Access Code
            </label>
            <input id="password" class="block mt-1 w-full bg-black border border-gray-800 text-white focus:border-red-600 focus:ring-0 rounded-none transition py-3 px-4" 
                   type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded-none border-gray-800 bg-black text-red-600 shadow-sm focus:ring-0" name="remember">
                <span class="ml-2 text-[10px] text-gray-500 uppercase font-bold tracking-widest italic">Maintain Session</span>
            </label>
        </div>

        <div class="flex flex-col space-y-4">
            <button type="submit" class="w-full bg-[#e32b2b] hover:bg-[#ff1a1a] text-white font-black py-4 uppercase tracking-[0.3em] transition-all transform hover:scale-[1.02] active:scale-95 shadow-lg">
                Login
            </button>

            @if (Route::has('password.request'))
                <a class="text-[9px] text-gray-600 hover:text-red-500 uppercase font-bold tracking-widest text-center transition" href="{{ route('password.request') }}">
                    Forgot Access Code?
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>