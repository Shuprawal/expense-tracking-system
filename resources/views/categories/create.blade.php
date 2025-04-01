
<x-app-layout>
    <h1>Create Category</h1>

    <form action="{{ route('categories.store') }}" class="card p-2" method="POST">
        @csrf

        <div class="m-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />

        </div>



        <x-primary-button>
            {{ __('Create') }}
        </x-primary-button>

    </form>

</x-app-layout>
