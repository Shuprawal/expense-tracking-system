<x-app-layout>

    <div class="py-12 m-4 p-4">
        <h1>Create Budget</h1>
        <form action="{{ route('budgets.store') }}" method="POST">
            @csrf

{{--            <div class="form-group mb-3">--}}
{{--                <label for="amount" class="form-label">Amount</label>--}}
{{--                <input type="text" value="{{old('amount')}}" placeholder="Initial budget" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount">--}}
{{--                @error('amount')--}}
{{--                <p class="invalid-feedback">{{ $message }}</p>--}}
{{--                @enderror--}}
{{--            </div>--}}
            <div class="form-group mb-3">
                <label for="name">Limit</label>
                <input type="text" class="form-control @error('limit') is-invalid @enderror" name="limit" id="limit" placeholder="Enter limit for budget">
                @error('limit')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Alert</label>
                <input type="text" class="form-control @error('alert') is-invalid @enderror" name="alert" id="alert" placeholder="Enter alert for budget">
                @error('alert')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary m-2">Submit</button>
        </form>
    </div>

</x-app-layout>
