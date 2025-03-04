<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>



    <h2>{{ Auth()->user()->email }}</h2>
    <div class="m-4 p-4 card shadow-sm" style="background-color: white;">
        <h3 class="">Income Categories: </h3>
        @forelse ($categoryIncomes as $category)

            <h3 class="">{{ $category->name }}</h3>


            @empty
            <h2>No categories</h2>

        @endforelse

    </div>

    <div class="m-4 p-4 card shadow-sm" style="background-color: white;">
        <h3 class="">Expenses Categories: </h3>
        @forelse ($categoryExpenses as $category)

            <h3 class="">{{ $category->name }}</h3>


        @empty
            <h2>No categories</h2>

        @endforelse

    </div>
            <x-nav-link class="" href="{{ route('categories.create') }}">
                {{ __('Create Categories') }}
            </x-nav-link>






</x-app-layout>
