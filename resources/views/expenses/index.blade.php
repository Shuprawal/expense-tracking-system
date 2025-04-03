
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
    <x-search :route="'expenses.search'"/>


    <x-month-select :route="'expenses.index'" :parameters="[]" />
    <x-date-duration :route="'expenses.index'" :parameters="[]" />
    <h3>list of expenses for month {{ \Carbon\Carbon::create()->month((int)$selectedMonth)->format('F') }} are:</h3>
    @forelse($expenses as $expense)
        <div class="card m-2 p-2">
            <div class="card-header">
{{--                @dd($expense->category->name )--}}
                @if($expense->category)
                    {{ $expense->category->name }}
                @else
                    <span class="text-danger">Category not found</span>
                @endif
                {{$expense->date}}
            </div>
            <div class="card-body">
                <div>
                    <p>{{$expense->description}}</p>
                    <p>{{$expense->amount}}</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <x-delete-button :route="'expenses.destroy'" :parameters="$expense->id" />

                    <a href="{{route('expenses.edit',$expense->id)}}" class="btn btn-dark">edit</a>
                </div>

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


