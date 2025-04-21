<x-app-layout>
    <div class="container m-4 p-4">
        <h1 class="fw-bold">
            Forecast Expenses for {{ \Carbon\Carbon::create()->month((int)$selectedMonth)->format('F') }}
        </h1>




        @if( $incomeSource=='forecastIncome')
            <h4>Forecast Income: {{ number_format($totalIncome, 2) }}</h4>
            <div class="my-3">
                <a href="{{ route('forecasts.edit', $income) }}" class="btn btn-primary">Change Income</a>
            </div>
        @else
            <h4>Total Income: {{ number_format($totalIncome, 2) }}</h4>
        @endif



        <x-month-select :route="'forecasts.report'" :parameters="[]" />

        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Percentage</th>
                            <th>Amount to Spend</th>
                            <th>Spend %</th>
                            <th>Actual Spend</th>
                            <th>Remaining</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $expense['name'] }}</td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <span>{{ $expense['percentage'] }}%</span>
                                        <form action="{{ route('category.forecast.edit', $expense['category_id']) }}" method="get" class="ms-2">
                                            <input type="hidden" name="date" value="{{ $selectedMonth }}">
                                            <input type="hidden" name="category_id" value="{{ $expense['category_id'] }}">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary p-1">
                                                <i class="bi bi-pen-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>


                                <td>{{ number_format($expense['amount'], 2) }}</td>
                                <td>{{ $expense['spendPercentage'] }}%</td>
                                <td>{{ number_format($expense['spend'], 2) }}</td>
                                <td>{{ number_format($expense['remaining'], 2) }}</td>

                                <td>
{{--                                    <form action="{{ route('forecasts.detach') }}" method="POST">--}}
{{--                                        @csrf--}}
{{--                                        @method('DELETE')--}}
{{--                                        <input type="hidden" name="category_id" value="{{ $expense['category_id'] }}">--}}
{{--                                        <input type="hidden" name="date" value="{{ $selectedMonth }}">--}}
{{--                                        <button type="submit" class="btn btn-outline-danger">--}}
{{--                                            <i class="bi bi-trash"></i>--}}
{{--                                        </button>--}}
{{--                                    </form>--}}
                                    <x-delete-button
                                        :route="'forecasts.detach'"
                                        :parameters="['category_id' => $expense['category_id'], 'date' => $selectedMonth]"
                                        :title="'Delete Category'"
                                        :message="'Are you sure you want to delete this Category? This cannot be undone.'"
                                    />
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
