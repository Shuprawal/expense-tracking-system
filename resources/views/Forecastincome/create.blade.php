<x-app-layout>
    <div class="m-4 p-4">
        <h1 class="mb-4 fw-bold">Income Amount </h1>
        <form action="{{ route('forecasts.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="type">Select income type</label>
                <select class="form-select" id="type" name="type">
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" value="{{old('amount')}}" placeholder="Rs" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount">
                @error('amount')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>


</x-app-layout>
