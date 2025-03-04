<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Expenses') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Category
            </button>
            <ul class="dropdown-menu">
                @forelse($categories as $category)
                    <li><a class="dropdown-item" href="{{route('categories.show',$category->id)}}">{{ $category->name  }}</a></li>
                @empty
                @endforelse


            </ul>
        </div>
    </div>


    <div class="m-4 p-4  shadow-sm" style="background-color: white;">
        @forelse ($expenses as $expense)

            <div class="card m-2">
                <div class="card-header row justify-content-between">
                    <h3 class="col-md-4"> {{ $expense->category->name }}</h3>
                    <p class="col-md-4">Date: {{ $expense->date }}</p>
                </div>
                <p class="card-body">Cost:Rs {{$expense->amount}}</p>
            </div>



        @empty
            <h2>No Income</h2>

        @endforelse

        <div>
            <h3 class="fw-bold">Total budget: {{ auth()->user()->totalBudget() }}</h3>
        </div>

        <x-nav-link class="" href="{{ route('expenses.create') }}">
            {{ __('New expenses') }}
        </x-nav-link>

    </div>




</x-app-layout>
