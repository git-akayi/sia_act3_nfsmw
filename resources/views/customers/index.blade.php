<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-red-600 leading-tight uppercase tracking-widest">
                {{ __('Rockport PD: Blacklist Database') }}
            </h2>
            <a href="{{ route('customers.create') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-4 rounded shadow-lg transition duration-150">
                + REGISTER NEW RIVAL
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-2xl sm:rounded-lg border border-gray-800">
                <div class="p-6 text-gray-100">
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-900/30 border-l-4 border-green-500 text-green-200 text-sm uppercase font-bold">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-700 bg-black text-xs uppercase tracking-[0.2em] text-gray-500">
                                    <th class="p-4 text-center">Rank</th>
                                    <th class="p-4">Driver Details</th>
                                    <th class="p-4">Signature Ride</th>
                                    <th class="p-4">Bounty</th>
                                    <th class="p-4">Strength</th>
                                    <th class="p-4">Territory</th>
                                    <th class="p-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse ($customers as $customer)
                                    <tr class="hover:bg-gray-800/40 transition group">
                                        <td class="p-4 text-center">
                                            <div class="text-4xl font-black italic text-red-600 group-hover:scale-110 transition-transform">
                                                {{ $customer->blacklist_rank }}
                                            </div>
                                        </td>

                                        <td class="p-4">
                                            <div class="text-orange-400 font-mono font-bold uppercase tracking-tighter text-lg leading-none">
                                                {{ $customer->alias }}
                                            </div>
                                            <div class="text-[10px] text-gray-500 uppercase mt-1">{{ $customer->name }}</div>
                                        </td>

                                        <td class="p-4">
                                            <span class="text-sm text-gray-300 italic font-medium">
                                                {{ $customer->car }}
                                            </span>
                                        </td>

                                        <td class="p-4">
                                            <div class="text-sm font-bold text-green-500 font-mono">
                                                ${{ number_format($customer->bounty) }}
                                            </div>
                                        </td>

                                        <td class="p-4 text-xs font-bold uppercase tracking-tight text-gray-400">
                                            {{ $customer->strength }}
                                        </td>

                                        <td class="p-4">
                                            <span class="text-xs text-red-500/80 italic font-bold uppercase">
                                                {{ $customer->territory }}
                                            </span>
                                        </td>

                                        <td class="p-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('customers.edit', $customer->id) }}" class="text-[10px] bg-gray-700 hover:bg-white hover:text-black px-2 py-1 rounded font-bold uppercase transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Confirm take-down?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[10px] bg-red-900/40 hover:bg-red-600 text-red-100 px-2 py-1 rounded font-bold uppercase transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-12 text-center text-gray-600 italic uppercase tracking-[0.5em]">
                                            Scanning for high-heat targets... No rivals found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            
            <div class="mt-6 flex justify-between items-center opacity-30 px-2">
                <span class="text-[9px] text-gray-500 uppercase tracking-widest">System Status: Connected</span>
                <span class="text-[9px] text-gray-500 uppercase tracking-widest text-right italic">Rockport PD Database v9.52.21</span>
            </div>
        </div>
    </div>
</x-app-layout>