
<x-app-layout>
<div class="container">
    <div class="mt-4">
        <a href="{{route('expenses.create')}}" class="btn btn-primary">Add</a>
    </div>
    <div class="mt-4">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Choose Category
            </button>
            <ul class="dropdown-menu dropdown-menu-dark">
                @forelse($categories as $category)
                    <li>
                        <a href="{{route('categories.show',$category->id,$selectedMonth)}}">{{$category->name}}</a>

                    </li>
                    @empty
                        <li>No expenses in any category</li>
                @endforelse
            </ul>
        </div>
    </div>
    <x-month-select :route="'expenses.index'" :parameters="[]" />
    <h3>list of expenses for month {{ \Carbon\Carbon::create()->month((int)$selectedMonth)->format('F') }} are:</h3>
    @forelse($expenses as $expense)
        <div class="card m-2 p-2">
            <div class="card-header">
                {{ $expense->category->name }}
                {{$expense->date}}
            </div>
            <div class="card-body  ">
                <p>{{$expense->description}}</p>
                <p>{{$expense->amount}}</p>
            </div>
        </div>
    @empty
    <li>No expenses for this month</li>
    @endforelse

    <div class="mt-4">
        {{ $expenses->appends(['month' => request('month')])->links() }}
    </div>
</div>


</x-app-layout>


