<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Todo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <form method="post" action="{{ route('todo.store') }}" class="">
                    @csrf
                    @method('post')

                    <div class="mb-6">
                        <x-input-label for="tittle" :value="__('tittle')" />
                        <x-text-input 
                            id="tittle" 
                            name="tittle" 
                            type="text" 
                            class="block w-full mt-1" 
                            required 
                            autofocus 
                            autocomplete="tittle" 
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('tittle')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>
                            {{ __('Save') }}
                        </x-primary-button>

                        <x-link-button href="{{ route('todo.index') }}">
                            {{ __('Cancel') }}
                        </x-link-button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>