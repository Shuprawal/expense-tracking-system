<x-app-layout>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow rounded-4">
                    <div class="card-body p-5">
                        <h2 class="mb-3 text-primary">Add New Expense</h2>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-muted">
                                For {{ \Carbon\Carbon::create()->month((int)$selectedMonth)->format('F') }}
                            </h5>
                            <x-month-select :route="'expenses.create'" />
                        </div>

                        <form action="{{ route('expenses.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="amount" class="form-label fw-semibold">Amount</label>
                                <input type="text" value="{{ old('amount') }}" placeholder="Rs"
                                       class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount">
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                          name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="date" class="form-label fw-semibold">Date</label>
                                <input type="date" value="{{ old('date') }}"
                                       class="form-control @error('date') is-invalid @enderror" id="date" name="date">
                                @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-semibold">Category</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" name="category_id">
                                    <option selected disabled>Choose a category</option>
                                    @forelse ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @empty
                                        <option selected disabled>No categories found for this month</option>
                                    @endforelse
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <input type="hidden" name="month" value="{{ $selectedMonth }}">

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                    Submit Expense
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
