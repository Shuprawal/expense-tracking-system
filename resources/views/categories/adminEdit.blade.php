
<x-app-layout>


    <form action="{{ route('categories.adminUpdate',$category->id) }}" class="card p-2" method="POST">
        <h1>Create Category</h1>
        @csrf
        @method('PUT')

        <div class="m-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="category" :value="old('name',$category)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('category')" class="mt-2" />

        </div>



        <x-primary-button >
            {{ __('Edit') }}
        </x-primary-button>

    </form>

</x-app-layout>
