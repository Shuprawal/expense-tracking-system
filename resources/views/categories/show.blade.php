<x-app-layout>
    <div class="container">
        <h1>{{$category->name}}</h1>
{{--        <div class="mt-4">--}}
{{--            <a href="{{route('expenses.create')}}" class="btn btn-primary">Add</a>--}}
{{--        </div>--}}
{{--        <x-month-select :route="'categories.show'" :parameters="['category' => $category]" />--}}

        <x-date-duration :route="'categories.show'" :parameters="['category' => $category]" />
{{--        <h3 class="fw-bold"> Expenses for :{{$category->name}} from {{ \Carbon\Carbon::parse($start)->format('Y-m-d') }} to {{ \Carbon\Carbon::parse($end)->format('Y-m-d') }}</h3>--}}
        <h3 class="fw-bold"> Expenses for :{{$category->name}} from {{ \Carbon\Carbon::parse($start)->format('F j, Y') }} to {{ \Carbon\Carbon::parse($end)->format('F j, Y') }}</h3>
        @forelse($expenses as $expense)

            <div class="card mb-3">
                <div class="card-header">
                    {{ \Carbon\Carbon::parse($expense->date)->format('l, F j, Y') }}
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
