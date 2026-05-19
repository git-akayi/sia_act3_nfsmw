<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-[#e32b2b] leading-tight uppercase tracking-[0.2em] italic">
            {{ __('Driver Command Center') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="flash-notification-box"></div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <h1 class="text-4xl font-black text-red-600 uppercase tracking-tighter italic drop-shadow-md">
                        RAP SHEET: {{ Auth::user()->name }}
                    </h1>
                    <div class="flex items-center gap-2">
                        <div class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-600 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                        </div>
                        <span class="text-[9px] font-black text-red-600 uppercase tracking-widest italic hidden sm:block">Live Link Active</span>
                    </div>
                </div>
                
                <button onclick="toggleTunerPanel()" 
                    class="bg-black hover:bg-[#1c1c1c] text-gray-400 hover:text-red-500 font-black text-[11px] uppercase tracking-wider py-2 px-4 border border-gray-800 transition-all flex items-center gap-2 italic select-none">
                    <span>// TOGGLE ECU TUNER</span>
                    <svg id="tuner-chevron" class="w-3 h-3 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <div class="bg-[#222222] border-l-4 border-red-600 p-4 shadow-xl"><p class="text-[9px] text-gray-400 uppercase font-bold tracking-widest mb-1">Rank</p><p class="text-2xl font-black text-white">#{{ Auth::user()->blacklist_rank }}</p></div>
                <div class="bg-[#222222] border-l-4 border-red-600 p-4 shadow-xl"><p class="text-[9px] text-gray-400 uppercase font-bold tracking-widest mb-1">Bounty</p><p class="text-2xl font-black text-orange-500 font-mono tracking-tighter">${{ number_format(Auth::user()->bounty) }}</p></div>
                <div class="bg-[#222222] border-l-4 border-red-600 p-4 shadow-xl"><p class="text-[9px] text-gray-400 uppercase font-bold tracking-widest mb-1">Ride</p><p class="text-lg font-black text-white truncate">{{ Auth::user()->signature_car }}</p></div>
                <div class="bg-[#222222] border-l-4 border-red-600 p-4 shadow-xl"><p class="text-[9px] text-gray-400 uppercase font-bold tracking-widest mb-1">Area</p><p class="text-lg font-black text-red-600 truncate">{{ Auth::user()->territory }}</p></div>
                <div class="bg-[#222222] border-l-4 border-red-600 p-4 shadow-xl"><p class="text-[9px] text-gray-400 uppercase font-bold tracking-widest mb-1">Specialty</p><p class="text-lg font-black text-white truncate">{{ Auth::user()->race_specialty }}</p></div>
                <div class="bg-[#222222] border-l-4 border-red-600 p-4 shadow-xl"><p class="text-[9px] text-gray-400 uppercase font-bold tracking-widest mb-1">Garage</p><p class="text-2xl font-black text-white font-mono">{{ Auth::user()->cars_owned }}</p></div>
            </div>

            <div id="tuner-panel" class="max-h-0 overflow-hidden transition-all duration-500 mt-0 opacity-0">
                <div class="bg-[#181818] border border-gray-900 p-6 shadow-2xl mt-8">
                    <form method="POST" action="{{ route('dashboard.tune-stats') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                        @csrf @method('PATCH')
                        
                        <div><label class="block text-[10px] text-gray-500 uppercase font-black tracking-wider mb-1">Rank</label>
                            <input type="number" name="blacklist_rank" value="{{ Auth::user()->blacklist_rank }}" class="w-full bg-black border-gray-800 text-white p-2 text-sm"></div>
                        <div><label class="block text-[10px] text-gray-500 uppercase font-black tracking-wider mb-1">Bounty</label>
                            <input type="number" name="bounty" value="{{ Auth::user()->bounty }}" class="w-full bg-black border-gray-800 text-orange-500 p-2 text-sm"></div>
                        <div><label class="block text-[10px] text-gray-500 uppercase font-black tracking-wider mb-1">Ride</label>
                            <input type="text" name="signature_car" value="{{ Auth::user()->signature_car }}" class="w-full bg-black border-gray-800 text-white p-2 text-sm"></div>
                        <div><label class="block text-[10px] text-gray-500 uppercase font-black tracking-wider mb-1">Area</label>
                            <input type="text" name="territory" value="{{ Auth::user()->territory }}" class="w-full bg-black border-gray-800 text-red-500 p-2 text-sm"></div>
                        
                        <div><label class="block text-[10px] text-gray-500 uppercase font-black tracking-wider mb-1">Specialty</label>
                            <select name="race_specialty" class="w-full bg-black border-gray-800 text-white p-2 text-sm">
                                <option value="Sprint" {{ Auth::user()->race_specialty == 'Sprint' ? 'selected' : '' }}>Sprint</option>
                                <option value="Circuit" {{ Auth::user()->race_specialty == 'Circuit' ? 'selected' : '' }}>Circuit</option>
                                <option value="Drag" {{ Auth::user()->race_specialty == 'Drag' ? 'selected' : '' }}>Drag</option>
                                <option value="Drift" {{ Auth::user()->race_specialty == 'Drift' ? 'selected' : '' }}>Drift</option>
                                <option value="Speedtrap" {{ Auth::user()->race_specialty == 'Speedtrap' ? 'selected' : '' }}>Speedtrap</option>
                                <option value="Knockout" {{ Auth::user()->race_specialty == 'Knockout' ? 'selected' : '' }}>Knockout</option>
                                <option value="Tollbooth" {{ Auth::user()->race_specialty == 'Tollbooth' ? 'selected' : '' }}>Tollbooth</option>
                                @if(Auth::user()->blacklist_rank == 1)
                                    <option value="Everything" {{ Auth::user()->race_specialty == 'Everything' ? 'selected' : '' }}>Everything</option>
                                @endif
                            </select></div>

                        <div><label class="block text-[10px] text-gray-500 uppercase font-black tracking-wider mb-1">Garage</label>
                            <input type="number" name="cars_owned" value="{{ Auth::user()->cars_owned }}" class="w-full bg-black border-gray-800 text-white p-2 text-sm"></div>

                        <div class="md:col-span-6 flex justify-end mt-4">
                            <button type="submit" class="bg-red-700 text-white text-xs font-black uppercase px-6 py-3 italic hover:bg-red-600 transition">Commit Changes</button>
                        </div>
                    </form>
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