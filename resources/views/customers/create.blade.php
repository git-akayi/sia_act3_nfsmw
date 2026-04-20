<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-600 dark:text-red-500 leading-tight uppercase tracking-widest">
            {{ __('Add New Blacklist Rival') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 border-l-4 border-red-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-100">

                    <form method="POST" action="{{ route('customers.store') }}">
                        @csrf

                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Driver Full Name')"
                                class="text-gray-400 uppercase text-xs font-bold" />
                            <x-text-input id="name" name="name" type="text"
                                class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-red-500 focus:ring-red-500"
                                :value="old('name')" required autofocus placeholder="e.g. Clarence Callahan" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="alias" :value="__('Alias / Street Name')"
                                    class="text-gray-400 uppercase text-xs font-bold" />
                                <x-text-input id="alias" name="alias" type="text"
                                    class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-red-500 focus:ring-red-500"
                                    :value="old('alias')" required placeholder="e.g. Razor" />
                                <x-input-error class="mt-2" :messages="$errors->get('alias')" />
                            </div>

                            <div>
                                <x-input-label for="blacklist_rank" :value="__('Blacklist Rank (#)')"
                                    class="text-gray-400 uppercase text-xs font-bold" />
                                <x-text-input id="blacklist_rank" name="blacklist_rank" type="number" min="1" max="999"
                                    class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-red-500 focus:ring-red-500"
                                    :value="old('blacklist_rank')" required placeholder="1-999" />
                                <x-input-error class="mt-2" :messages="$errors->get('blacklist_rank')" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="car" :value="__('Signature Ride (Vehicle Data)')"
                                class="text-gray-400 uppercase text-xs font-bold" />
                            <x-text-input id="car" name="car" type="text"
                                class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-red-500 focus:ring-red-500"
                                :value="old('car')" required placeholder="e.g. BMW M3 GTR (E46)" />
                            <x-input-error class="mt-2" :messages="$errors->get('car')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="strength" :value="__('Driver Strength')"
                                    class="text-gray-400 uppercase text-xs font-bold" />
                                <x-text-input id="strength" name="strength" type="text"
                                    class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-red-500 focus:ring-red-500"
                                    :value="old('strength')" required placeholder="e.g. Sprint Races" />
                                <x-input-error class="mt-2" :messages="$errors->get('strength')" />
                            </div>

                            <div>
                                <x-input-label for="territory" :value="__('Controlled Territory')"
                                    class="text-gray-400 uppercase text-xs font-bold" />
                                <x-text-input id="territory" name="territory" type="text"
                                    class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-red-500 focus:ring-red-500"
                                    :value="old('territory')" required placeholder="e.g. Camden Beach" />
                                <x-input-error class="mt-2" :messages="$errors->get('territory')" />
                            </div>
                        </div>

                        <div class="mb-8">
                            <x-input-label for="bounty" :value="__('Bounty')"
                                class="text-gray-400 uppercase text-xs font-bold" />
                            <x-text-input id="bounty" name="bounty" type="number"
                                class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-red-500 focus:ring-red-500"
                                :value="old('bounty')" required placeholder="e.g. 10000000" />
                            <x-input-error class="mt-2" :messages="$errors->get('bounty')" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('customers.index') }}"
                                class="text-sm text-gray-400 hover:text-white mr-4 transition duration-150 ease-in-out">
                                {{ __('Abort Mission') }}
                            </a>

                            <x-primary-button
                                class="bg-red-600 hover:bg-red-700 active:bg-red-800 focus:border-red-900 ring-red-500">
                                {{ __('Register Rival') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>