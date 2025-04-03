<x-app-layout>
    <div class="m-4 p-4">
        <h1>Edit Expenses</h1>
        <form action="{{ route('expenses.update',$expense->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" value="{{$expense->amount}}" placeholder="Rs" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount">
                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                @error('amount')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea type="text"  class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{$expense->description}}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                @error('description')
                <p class="invalid-feedback">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" value="{{$expense->date}}"  class="form-control @error('date') is-invalid @enderror" id="date" name="date">
                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                @error('date')
                <p class="invalid-feedback">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>



                <select class="form-select" aria-label="Default select example" name="category_id">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>
            <button type="submit" class="btn btn-primary">Update Expenses</button>

        </form>
    </div>


</x-app-layout>
