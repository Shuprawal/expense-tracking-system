<x-app-layout>
    <div class="container m-4 p-4">
        <h1 class="fw-bold">Forecast Expenses {{ $selectedMonth }}</h1>
        <h4>Forecast Income:{{$totalIncome , 2}}</h4>

        <div class="">
            <a href="{{route('forecasts.edit',$income)}}" class="btn btn-primary">Add Expense</a>
        </div>

        <div class="mt-4">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Choose Month
                </button>
                <ul class="dropdown-menu dropdown-menu-dark">
                    @for($i=1; $i<=12; $i++)
                        <li>
                            <form action="{{route('forecasts.report',$i)}}" method="get">
                                @csrf

                                <input type="hidden" name="month" value="{{$i}}">
                                <button class="dropdown-item " type="submit">{{$i}}</button>
                            </form>

                        </li>
                    @endfor
                </ul>
            </div>
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
                            <th scope="col">Actual spend</th>
                            <th scope="col">Remaining</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{$expense['name']}}</td>
                                <td>{{$expense['percentage']}}%
                                    <a href="{{route('forecasts.edit',$income->id)}}" class="btn btn-primary"><i class="bi bi-pen-fill"></i></a>

                                </td>
                                <td>
                                    {{$expense['amount']}}
                                </td>
                                <td>
                                    {{ $expense['spend']}}
                                </td>
                                <td>
                                    {{ $expense['remaining']}}
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
