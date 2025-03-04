<x-app-layout>
    <div class="container m-4 p-4">
        <h1 class="fw-bold">Forecast Expenses</h1>
        <h4>Forecast Income:{{$totalIncome , 2}}</h4>

        <div class="">
            <a href="{{route('forecasts.edit',$income)}}" class="btn btn-primary">Add Expense</a>
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
                                {{--                                        <td>{{$tag->user->name}}</td>--}}
                                <td>{{$expense['percentage']}}%
                                    <a href="{{route('forecasts.edit',$income->id)}}" class="btn btn-primary"><i class="bi bi-pen-fill"></i></a>

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
