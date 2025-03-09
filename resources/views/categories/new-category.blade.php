<x-app-layout>
    <div class="container mt-5 p-4 border rounded shadow bg-white" style="max-width: 600px;">
        <h2 class="mb-4 text-center">Select Categories</h2>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Choose Categories</label>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="selected_categories[]" value="{{ $category->name }}" id="category_{{ $category->id }}">
                            <label class="form-check-label" for="category_{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('selected_categories')" class="mt-2" />
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
                <x-input-error :messages="$errors->get('date')" class="mt-2" />
            </div>

            <div class="mb-3">
                <label for="new_category" class="form-label">Add New Category</label>
                <div id="new-category-container">
                    <input type="text" class="form-control" name="new_categories[]" placeholder="Enter Category Name">
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addCategory()">+ Add Another</button>
                <x-input-error :messages="$errors->get('new_categories')" class="mt-2" />
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script>
        function addCategory() {
            var container = document.getElementById('new-category-container');
            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'new_categories[]';
            input.className = 'form-control mt-2';
            input.placeholder = 'Enter Category Name';
            container.appendChild(input);
        }
    </script>
</x-app-layout>
