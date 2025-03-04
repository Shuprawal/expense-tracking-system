<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Incomes') }}
        </h2>
    </x-slot>




    <div class="m-4 p-4  shadow-sm" style="background-color: white;">
        @forelse ($incomes as $income)
            <div class="card mb-4">
                <div class="card-header row justify-content-between">
                    <h3 class="col-md-4">{{ $income->category->name }}</h3>
                    <p class="col-md-4">Date: {{ $income->date }}</p>
                </div>

                <p class="card-body">Income:{{$income->amount}}</p>
            </div>


        @empty
            <h2>No Income</h2>

        @endforelse

        <div>
            <h3 class="fw-bold">Total budget: {{ auth()->user()->totalBudget() }}</h3>
        </div>

        <x-nav-link class="" href="{{ route('incomes.create') }}">
            {{ __('Add income') }}
        </x-nav-link>

    </div>




</x-app-layout>
