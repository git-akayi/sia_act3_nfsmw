<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-600 leading-tight uppercase tracking-widest">
            {{ __('Update Criminal File: ') }} {{ $customer->alias }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 border-l-4 border-orange-500 overflow-hidden shadow-2xl sm:rounded-lg">
                <div class="p-8 text-gray-100">
                    
                    <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Driver Full Name')" class="text-gray-400 uppercase text-xs font-bold" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-orange-500 focus:ring-orange-500" :value="old('name', $customer->name)" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="alias" :value="__('Alias / Street Name')" class="text-gray-400 uppercase text-xs font-bold" />
                                <x-text-input id="alias" name="alias" type="text" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-orange-500 focus:ring-orange-500" :value="old('alias', $customer->alias)" required />
                            </div>

                            <div>
                                <x-input-label for="blacklist_rank" :value="__('Blacklist Rank (#)')" class="text-gray-400 uppercase text-xs font-bold" />
                                <x-text-input id="blacklist_rank" name="blacklist_rank" type="number" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-orange-500 focus:ring-orange-500" :value="old('blacklist_rank', $customer->blacklist_rank)" required />
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="car" :value="__('Signature Ride')" class="text-gray-400 uppercase text-xs font-bold" />
                            <x-text-input id="car" name="car" type="text" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-orange-500 focus:ring-orange-500" :value="old('car', $customer->car)" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="strength" :value="__('Driver Strength')" class="text-gray-400 uppercase text-xs font-bold" />
                                <x-text-input id="strength" name="strength" type="text" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-orange-500 focus:ring-orange-500" :value="old('strength', $customer->strength)" required />
                            </div>

                            <div>
                                <x-input-label for="territory" :value="__('Controlled Territory')" class="text-gray-400 uppercase text-xs font-bold" />
                                <x-text-input id="territory" name="territory" type="text" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-orange-500 focus:ring-orange-500" :value="old('territory', $customer->territory)" required />
                            </div>
                        </div>

                        <div class="mb-8">
                            <x-input-label for="bounty" :value="__('Bounty Amount')" class="text-gray-400 uppercase text-xs font-bold" />
                            <x-text-input id="bounty" name="bounty" type="number" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-orange-500 focus:ring-orange-500" :value="old('bounty', $customer->bounty)" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('customers.index') }}" class="text-sm text-gray-400 hover:text-white mr-4 transition uppercase">
                                {{ __('Cancel') }}
                            </a>
                            
                            <x-primary-button class="bg-orange-600 hover:bg-orange-700 active:bg-orange-800 ring-orange-500">
                                {{ __('Update File') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>