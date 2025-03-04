<x-app-layout>
    <div class="m-4 p-4">
        <h1>New Expenses</h1>
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" value="{{old('amount')}}" placeholder="Rs" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount">
                @error('amount')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description"></textarea>
                @error('description')
                <p class="invalid-feedback">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date">
                @error('date')
                <p class="invalid-feedback">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select @error('category_id') is-invalid @enderror" aria-label="Default select example" name="category_id">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="invalid-feedback">{{$message}}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>

        </form>
    </div>


</x-app-layout>
