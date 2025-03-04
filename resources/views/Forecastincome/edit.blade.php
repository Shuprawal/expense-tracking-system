<x-app-layout>
    <div class="m-4 p-4">
        <h1>Create Income</h1>
        <form action="{{ route('forecasts.update',$income->id) }}" method="POST">
            @csrf
            @METHOD('PUT')
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" value="{{old('amount',$income->amount)}}" placeholder="Rs" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount">
                @error('amount')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>


</x-app-layout>
