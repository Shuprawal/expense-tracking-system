
<x-app-layout>
{{--    @dd($start,$end)--}}
{{--    @dd($search)--}}

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-primary">Expenses </h1>
            <a href="{{ route('expenses.create') }}" class="btn btn-lg btn-success shadow">+ Add Expense</a>
        </div>

        <div class="d-flex flex-wrap gap-3 mb-4">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Choose Category
                </button>
                <ul class="dropdown-menu">
                    @forelse($categories as $category)
                        <li><form class="dropdown-item" action="{{route('expenses.index')}}" method="get">
                                <input type="hidden" name="category" value="{{$category->id}}">
{{--                                <input type="hidden" name="category" value="{{ $category->name }}">--}}
                                <button type="submit">{{ $category->name }}</button>
                            </form></li>
{{--                        <li><a class="dropdown-item" href="{{ route('expenses.index', $category->id) }}">{{ $category->name }}</a></li>--}}
                    @empty
                        <li class="dropdown-item text-muted">No expenses in any category</li>
                    @endforelse
               </ul>

            </div>
            <x-search :route="'expenses.index'"/>
            <x-date-duration :route="'expenses.index'" :parameters="[]" />
        </div>

{{--        <h3 class="text-secondary">Expenses for {{ \Carbon\Carbon::create()->month((int)$selectedMonth)->format('F') }}:</h3>--}}

        <div class="row">
            @forelse($expenses as $expense)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 rounded-lg mb-3">
                        <div class="card-header bg-dark text-white d-flex justify-content-between">
                            <span>{{ $expense->category->name }}</span>
                            <span class="badge bg-light text-dark">{{ $expense->date }}</span>
                        </div>
                        <div class="card-body">
                            <p class="fw-bold text-primary">${{ number_format($expense->amount, 2) }}</p>
                            <p class="text-muted">{{ $expense->description }}</p>
                            <div class="d-flex justify-content-between mt-3">
                                <x-delete-button :route="'expenses.destroy'" :parameters="$expense->id" />
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-outline-dark btn-sm">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    <p>No expenses for this month</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $expenses->appends(['inputText' => request('inputText'),'category'=>request('category')])->links() }}
        </div>
    </div>
</x-app-layout>
