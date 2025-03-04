
<x-app-layout>
    <h1>Create Category</h1>

    <form action="{{ route('categories.store') }}" class="card p-2" method="POST">
        @csrf

        <div class="m-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />

        </div>

{{--        <div class="mb-3">--}}
{{--            <label for="type" class="form-label">Category Type</label>--}}
{{--            <select class="form-select @error('type') is-invalid @enderror" aria-label="Default select example" name="type">--}}
{{--                    <option value="income">Income</option>--}}
{{--                    <option value="expense">Expense</option>--}}
{{--            </select>--}}
{{--            @error('category_id')--}}
{{--            <p class="invalid-feedback">{{$message}}</p>--}}
{{--            @enderror--}}
{{--        </div>--}}

        <x-primary-button>
            {{ __('Create') }}
        </x-primary-button>
{{--        <button type="submit" class="btn btn-primary">Submit</button>--}}
    </form>

</x-app-layout>
