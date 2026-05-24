<x-app-layout>
    <div class="text-white min-h-screen p-6 font-sans">
        
        <div class="max-w-4xl mx-auto">
            
            <h1 class="text-2xl font-black text-[#e32b2b] uppercase tracking-[0.2em] italic mb-6">
                ROCKPORT PD: BLACKLIST DATABASE
            </h1>

            <h2 class="text-xl font-black text-red-600 uppercase tracking-wide italic mb-4">
                LIVE TARGET LEADERBOARD
            </h2>

            <div class="overflow-x-auto border border-gray-900 bg-[#0d0d0d] shadow-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-800 text-[9px] uppercase font-black tracking-widest text-gray-500 bg-black">
                            <th class="py-3 px-5 w-20">Rank</th>
                            <th class="py-3 px-4">Driver Details</th>
                            <th class="py-3 px-4 w-48">Reppin'</th>
                            <th class="py-3 px-12 w-48">Bounty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900/40">
                        @foreach($users as $index => $driver)
                            <tr onclick="handleRowClick(this)"
                                data-name="{{ e($driver->name) }}"
                                data-rank="{{ $index + 1 }}"
                                data-avatar="{{ asset('images/avatars/' . ($driver->avatar ?? 'nfsmw.jpg')) }}"
                                data-reppin="{{ e($driver->territory) }}"
                                data-garage="{{ $driver->cars_owned }}"
                                data-bounty="${{ number_format($driver->bounty) }}"
                                data-ride="{{ e($driver->signature_car ?? 'N/A') }}"
                                data-specialty="{{ e($driver->race_specialty ?? 'N/A') }}"
                                class="hover:bg-[#1a1a1a]/60 cursor-pointer transition-colors group">
                                
                                <td class="py-4 px-5 font-black italic text-lg text-gray-400 group-hover:text-red-500 transition-colors">
                                    #{{ $index + 1 }}
                                </td>
                                
                                <td class="py-4 px-4 flex items-center gap-4">
                                    <img src="{{ asset('images/avatars/' . ($driver->avatar ?? 'nfsmw.jpg')) }}" 
                                         class="driver-avatar w-12 h-12 object-cover border-2 border-red-600/40 shadow-md group-hover:border-red-500 transition-colors flex-shrink-0" 
                                         alt="Avatar">
                                    <div>
                                        <span class="font-black tracking-wider uppercase block text-sm text-white leading-tight">
                                            {{ $driver->name }}
                                            @if(Auth::id() === $driver->id)
                                                <span class="bg-red-600 text-[8px] px-1 py-0.5 ml-1 text-black font-black italic rounded-sm inline-block align-middle">YOU</span>
                                            @endif
                                        </span>
                                        <span class="text-[9px] text-gray-500 uppercase font-mono tracking-tight mt-0.5 block">Blacklist Rank: #{{ $index + 1 }}</span>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-4 font-black uppercase text-red-600 italic tracking-wide text-xs">
                                    {{ $driver->territory }}
                                </td>
                                
                                <td class="py-4 px-12 font-mono font-black text-orange-500 tracking-tighter text-sm">
                                    ${{ number_format($driver->bounty) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    <div id="driver-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300 p-4">
        <div class="w-full max-w-sm bg-[#151515] border border-red-600/40 shadow-[0_0_30px_rgba(220,38,38,0.15)] flex flex-col transform scale-95 transition-transform duration-300 overflow-hidden">
            
            <div class="flex justify-between items-center bg-[#0d0d0d] border-b border-gray-900 p-4">
                <button onclick="closeDriverProfile()" class="text-red-600 hover:text-white transition bg-[#1c1c1c] p-2 border border-red-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <span class="text-[10px] font-black tracking-[0.2em] text-gray-500 uppercase italic">Dashboard / Blacklist</span>
                <span id="modal-top-alias" class="text-xs font-black px-3 py-1 bg-[#222] border border-gray-800 text-white italic tracking-widest uppercase">DRIVER</span>
            </div>

            <div class="p-6 space-y-5 flex-grow overflow-y-auto">
                <div>
                    <div class="text-[9px] font-black text-red-600 uppercase tracking-[0.25em] mb-2 flex items-center gap-1">
                        <span class="inline-block w-1.5 h-3 bg-red-600"></span> Driver Profile
                    </div>
                    <div class="bg-black/40 border border-gray-900 p-4 flex items-center gap-4">
                        <div class="w-16 h-16 border border-red-600/60 bg-black flex-shrink-0 overflow-hidden">
                            <img id="modal-avatar" src="" class="w-full h-full object-cover" alt="Driver Target">
                        </div>
                        <div>
                            <h3 id="modal-alias" class="text-2xl font-black text-white uppercase italic tracking-wide">--</h3>
                            <p class="text-[10px] font-black text-gray-500 uppercase">Blacklist Rank: <span id="modal-rank" class="text-white font-mono">#--</span></p>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-[9px] font-black text-red-600 uppercase tracking-[0.25em] mb-2 flex items-center gap-1">
                        <span class="inline-block w-1.5 h-3 bg-red-600"></span> Tracked Metrics
                    </div>
                    <div class="border border-gray-900 divide-y divide-gray-900 bg-black/20 text-xs font-black uppercase italic">
                        <div class="p-3 bg-black/40 text-gray-500 text-[10px] tracking-wider border-b border-gray-900">Database Entry</div>
                        <div class="flex justify-between p-3"><span class="text-gray-500">Rank</span><span id="modal-stat-rank" class="font-mono text-white">--</span></div>
                        <div class="flex justify-between p-3"><span class="text-gray-500">Bounty</span><span id="modal-stat-bounty" class="font-mono text-orange-500">--</span></div>
                        <div class="flex justify-between p-3"><span class="text-gray-500">Signature Ride</span><span id="modal-stat-ride" class="text-white tracking-wide uppercase">--</span></div>
                        <div class="flex justify-between p-3"><span class="text-gray-500">Reppin'</span><span id="modal-stat-reppin" class="text-red-600">--</span></div>
                        <div class="flex justify-between p-3"><span class="text-gray-500">Specialty</span><span id="modal-stat-specialty" class="text-cyan-400 uppercase tracking-wide">--</span></div>
                        <div class="flex justify-between p-3"><span class="text-gray-500">Cars Owned</span><span id="modal-stat-garage" class="font-mono text-white">--</span></div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-[#0a0a0a] border-t border-gray-900 text-center">
                <p class="text-[8px] font-mono text-gray-700 tracking-widest">ROCKPORT INTERNAL NETWORK SECURITY PROTOCOL V9.52.21</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fallbackUrl = "{{ asset('images/avatars/nfsmw.jpg') }}";
            
            document.querySelectorAll('.driver-avatar').forEach(img => {
                img.addEventListener('error', function() {
                    this.src = fallbackUrl;
                });
            });

            document.getElementById('modal-avatar').addEventListener('error', function() {
                this.src = fallbackUrl;
            });
        });

        function handleRowClick(rowElement) {
            const driverData = {
                name: rowElement.getAttribute('data-name'),
                rank: rowElement.getAttribute('data-rank'),
                avatar: rowElement.getAttribute('data-avatar'),
                reppin: rowElement.getAttribute('data-reppin'),
                garage: rowElement.getAttribute('data-garage'),
                bounty: rowElement.getAttribute('data-bounty'),
                ride: rowElement.getAttribute('data-ride'),
                specialty: rowElement.getAttribute('data-specialty')
            };
            openDriverProfile(driverData);
        }

        function openDriverProfile(driver) {
            const modal = document.getElementById('driver-modal');
            const card = modal.querySelector('.max-w-sm');

            document.getElementById('modal-top-alias').innerText = driver.name;
            document.getElementById('modal-alias').innerText = driver.name;
            document.getElementById('modal-rank').innerText = '#' + driver.rank;
            document.getElementById('modal-avatar').src = driver.avatar;
            
            document.getElementById('modal-stat-rank').innerText = '#' + driver.rank;
            document.getElementById('modal-stat-bounty').innerText = driver.bounty;
            document.getElementById('modal-stat-ride').innerText = driver.ride;
            document.getElementById('modal-stat-reppin').innerText = driver.reppin;
            document.getElementById('modal-stat-specialty').innerText = driver.specialty;
            document.getElementById('modal-stat-garage').innerText = driver.garage;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-95');
            }, 10);
        }

        function closeDriverProfile() {
            const modal = document.getElementById('driver-modal');
            const card = modal.querySelector('.max-w-sm');

            modal.classList.add('opacity-0');
            card.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        window.onclick = function(event) {
            const modal = document.getElementById('driver-modal');
            if (event.target === modal) {
                closeDriverProfile();
            }
        }
    </script>
</x-app-layout>