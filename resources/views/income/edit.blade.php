<x-app-layout>
    <div class="m-4 p-4">
        <h1>Create Income</h1>
        <form action="{{ route('incomes.update',$income->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" value="{{old('amount',$income->amount)}}" placeholder="Rs" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount">
                @error('amount')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{old('description',$income->description)}}</textarea>
                @error('description')
                <p class="invalid-feedback">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" value="{{old('date',$income->date)}}" class="form-control @error('date') is-invalid @enderror" id="date" name="date">
                @error('date')
                <p class="invalid-feedback">{{$message}}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>


</x-app-layout>
