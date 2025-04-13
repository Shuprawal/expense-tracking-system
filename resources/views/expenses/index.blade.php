
<x-app-layout>

    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-primary">Expenses </h1>
            <a href="{{ route('expenses.create') }}" class="btn btn-lg btn-dark shadow">+ Add Expense</a>
        </div>

       <button id="filter" class="btn btn-outline-secondary">Filter</button>

        <div id="filterContains" style="display: none" class="my-2 alert alert-info bg-opacity-40">
            <div class="d-flex flex-wrap gap-3 mb-4">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        @if(!empty($selectedCategoryName))
                            Selected  {{$selectedCategoryName}}
                        @else
                            Choose Category
                        @endif
                    </button>
                    <ul class="dropdown-menu">
                        @forelse($categories as $category)
                            <li><form class="dropdown-item" action="{{route('expenses.index')}}" method="get">
                                    <input type="hidden" name="category" value="{{$category->id}}">
                                    <input type="hidden" name="inputText" value="{{ $search}}">
                                    <input type="hidden" name="start" value="{{ $start}}">
                                    <input type="hidden" name="end" value="{{ $end}}">
                                    {{--                                <input type="hidden" name="category" value="{{ $category->name }}">--}}
                                    <button type="submit">{{ $category->name }}</button>
                                </form></li>
                            {{--                        <li><a class="dropdown-item" href="{{ route('expenses.index', $category->id) }}">{{ $category->name }}</a></li>--}}
                        @empty
                            <li class="dropdown-item text-muted">No expenses in any category</li>
                        @endforelse
                    </ul>

                </div>
                <x-date-duration
                    :route="'expenses.index'"
                    :parameters="[
                    'search'=>$search,
                    'inputText' => request('inputText'),
                    'category' => request('category')
                ]"
                />


                <a class="btn btn-success ms-5" href="{{route('expenses.index')}}" >clear filter</a>

            </div>
        </div>

        @php
            $filteredParameters = array_filter([
                'inputText' => request('inputText'),
                'start' => request('start'),
                'end' => request('end'),
                'category' => request('category'),
            ], fn($value) => filled($value));
        @endphp


        <x-search :route="'expenses.index'" :parameters="$filteredParameters" />


        {{--        <x-search--}}
{{--            :route="'expenses.index'"--}}
{{--            :parameters="[--}}
{{--                     'inputText' => request('inputText'),--}}
{{--                        'start' => request('start'),--}}
{{--                        'end' => request('end'),--}}
{{--                        'category' => request('category'),--}}
{{--                ]"--}}
{{--        />--}}

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
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-pen"></i></a>
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
            {{ $expenses->appends(['inputText' => request('inputText'),'category'=>request('category'),'start'=>request('start'),'end'=>request('end')])->links() }}
        </div>
    </div>


    <script>
        const filterShow = document.getElementById('filter');
        const filterBody = document.getElementById('filterContains');
        // const filterClose = document.getElementById('closeFilter');

        const hasFilters = '{{ request('inputText') || request('category') || request('start') || request('end') ? 'true' : '' }}';

        if (hasFilters) {
            filterBody.style.display = "block";
            filterShow.innerText = "Close Filter";
        }

        filterShow.addEventListener('click', () => {
            const isVisible = filterBody.style.display === "block";
            filterBody.style.display = isVisible ? "none" : "block";
            filterShow.innerText = isVisible ? "Filter" : "Close Filter";
        });


    </script>
</x-app-layout>
