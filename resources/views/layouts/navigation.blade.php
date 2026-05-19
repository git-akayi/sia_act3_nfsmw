<nav x-data="{ open: false }" class="bg-[#1a1a1a] border-b border-red-900/40 shadow-xl relative z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/nfs.png') }}"
                            class="h-12 w-auto filter drop-shadow-[0_0_8px_rgba(230,43,43,0.7)]" alt="RPD Logo">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="uppercase tracking-widest font-black italic text-gray-300 hover:text-red-500 transition">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')"
                        class="uppercase tracking-widest font-black italic text-gray-300 hover:text-red-500 transition">
                        {{ __('Blacklist') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-bold uppercase tracking-tighter text-gray-300 hover:text-white transition">
                            <div class="mr-3 text-right">
                                <div class="text-[9px] italic opacity-60 leading-none">LOGGED AS</div>
                                <div class="font-black italic">{{ Auth::user()->name }}</div>
                            </div>

                            <div class="relative">
                                <img class="h-10 w-10 rounded-sm object-cover border border-red-600/50 shadow-[0_0_10px_rgba(230,43,43,0.3)] bg-black"
                                    src="{{ asset('images/avatars/' . (Auth::user()->avatar ?? 'nfsmw.jpg')) }}"
                                    onerror="this.src='{{ asset('images/avatars/nfsmw.jpg') }}'"
                                    alt="{{ Auth::user()->name }}">
                                <div class="absolute inset-0 border border-white/10 pointer-events-none"></div>
                            </div>

                            <div class="ml-2">
                                <svg class="fill-current h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-[#111] border border-gray-800 py-1 shadow-2xl">
                            <x-dropdown-link :href="route('profile.edit')"
                                class="uppercase font-bold text-xs text-gray-400 hover:bg-red-600 hover:text-white transition">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="uppercase font-bold text-xs text-red-600 hover:bg-red-700 hover:text-white transition">
                                    {{ __('Disconnect') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>