<x-app-layout>
    <div class="container m-4 p-4">
        <h1 class="fw-bold">Forecast Expenses for {{\Carbon\Carbon::create()->month((int)$selectedMonth)->format('F')}}</h1>
        <h4>Forecast Income:{{$totalIncome , 2}}</h4>

        <div class="">
            <a href="{{route('forecasts.edit',$income)}}" class="btn btn-primary">Change Income</a>
        </div>


        <x-month-select :route="'forecasts.report'" :parameters="[]" />

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                        <tr>
                            <th scope="col">Category</th>
                            <th scope="col">Percentage</th>
                            <th scope="col">Amount to spend</th>
                            <th scope="col">Spend percentage</th>
                            <th scope="col">Actual spend</th>
                            <th scope="col">Remaining</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($expenses as $expense)
                            <tr >
                                <td>{{$expense['name']}}</td>
                                <td>{{$expense['percentage']}}%
{{--                                    <a href="{{route('forecasts.edit',$income->id)}}" class="btn btn-primary"><i class="bi bi-pen-fill"></i></a>--}}

                                </td>
                                <td>
                                    {{$expense['amount']}}
                                </td>
                                <td>
                                    {{$expense['spendPercentage']}}
                                </td>
                                <td>
                                    {{ $expense['spend']}}
                                </td>
                                <td>
                                    {{ $expense['remaining']}}
                                </td>
                                <td>


                                    <form action="{{route('forecasts.detach')}}" method="Post" >
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="category_id" value="{{$expense['category_id']}}">
                                        <input type="hidden" name="date" value="{{$selectedMonth}}">
                                        <button type="submit">Delete</button>
                                    </form>
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
