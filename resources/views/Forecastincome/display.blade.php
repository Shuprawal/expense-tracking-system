
{{--<x-app-layout>--}}
{{--    <div class="container my-4">--}}
{{--        <div class="d-flex justify-content-between align-items-center mb-4">--}}
{{--            <h1 class="fw-bold text-primary">Expenses</h1>--}}
{{--            <a href="{{ route('incomes.create') }}" class="btn btn-lg btn-success shadow">+ Add Income</a>--}}
{{--        </div>--}}

{{--        <div class="d-flex flex-wrap gap-3 mb-4">--}}

{{--            <x-search :route="'expenses.search'"/>--}}
{{--            <x-date-duration :route="'incomes.index'" :parameters="[]" />--}}
{{--        </div>--}}


{{--        <div class="row">--}}
{{--            @forelse($incomes as $expense)--}}
{{--                <div class="col-md-6 col-lg-4">--}}
{{--                    <div class="card shadow-sm border-0 rounded-lg mb-3">--}}
{{--                        <div class="card-header bg-dark text-white d-flex justify-content-between">--}}
{{--                            <span>{{ $expense->category->name }}</span>--}}
{{--                            <span class="badge bg-light text-dark">{{ $expense->date }}</span>--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <p class="fw-bold text-primary">${{ number_format($expense->amount, 2) }}</p>--}}
{{--                            <p class="text-muted">{{ $expense->description }}</p>--}}
{{--                            <div class="d-flex justify-content-between mt-3">--}}
{{--                                <x-delete-button :route="'expenses.destroy'" :parameters="$expense->id" />--}}
{{--                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-outline-dark btn-sm">Edit</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @empty--}}
{{--                <div class="col-12 text-center text-muted">--}}
{{--                    <p>No expenses for this month</p>--}}
{{--                </div>--}}
{{--            @endforelse--}}
{{--        </div>--}}

{{--        <div class="mt-4 d-flex justify-content-center">--}}
{{--            {{ $expenses->appends(['month' => request('month')])->links() }}--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</x-app-layout>--}}


<x-app-layout>
    <div class="container m-4 p-4">
        <h1 class="fw-bold">Forecast Expenses</h1>
        <h4>Forecast Income:{{$totalIncome , 2}}</h4>

        <div class="">
            <a href="{{route('forecasts.edit',$income)}}" class="btn btn-primary">Change Income</a>
        </div>


        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Category</th>
                            <th scope="col">Percentage</th>
                            <th scope="col">Amount to spend</th>


                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{$expense['name']}}</td>
                                <td>{{$expense['percentage']}}%
                                    {{--                                    <a href="{{route('forecasts.edit',$income->id)}}" class="btn btn-primary"><i class="bi bi-pen-fill"></i></a>--}}
                                </td>
                                <td>
                                    {{ $expense['amount']}}
                                </td>


                            </tr>
                        @endforeach




                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

