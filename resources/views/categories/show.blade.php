<x-app-layout>
    <div class="container">
        <div class="mt-4">
            <a href="{{route('expenses.create')}}" class="btn btn-primary">Add</a>
        </div>
        <x-month-select :route="'categories.show'" :parameters="['category' => $category]" />

        <h3 class="fw-bold"> Expenses for :{{$category->name}} for the month of {{\Carbon\Carbon::create()->month((int)$selectedDate)->format('F')}}</h3>
        @forelse($expenses as $expense)

            <div class="card">
                <div class="card-header">

                    {{\Carbon\Carbon::parse($expense->date)->format('D')}}- {{\Carbon\Carbon::parse($expense->date)->format('d')}}
                </div>
                <div class="card-body">
                   Amount:RS {{(int)$expense->amount}}<br>
                    {{$expense->description}}
                </div>
            </div>
        @empty
        <li>No expenses</li>
        @endforelse
    </div>
</x-app-layout>
