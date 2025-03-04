<x-app-layout>
    <div class="container mt-5 p-4 border rounded shadow bg-white" style="max-width: 600px;">
        <h2 class="mb-4 text-center">Select Categories</h2>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Choose Categories</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="name[]" value="{{ $category->name }}" id="category_{{ $category->id }}">
                            <label class="form-check-label" for="category_{{ $category->id }}" >
                                {{ $category->name }}
                            </label>
                            <x-input-error :messages="$errors->get('name[]')" class="mt-2" />

                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">
                    Date
                </label>
                <input type="date" class="form-control" id="date" name="date">
                <x-input-error :messages="$errors->get('date')" class="mt-2" />
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <div>
                    <input type="text" class="form-control" id="name" name="name[]" placeholder="Enter Category Name">
                    <div class="">
                        <button type="button" class="btn btn-danger btn-sm" onclick="addCategory()">Add Category</button>
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <script>
        function addCategory() {
            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'name[]';
            input.className = 'form-control';
            input.placeholder = 'Enter Category Name';
            document.querySelector('.d-flex.flex-wrap.gap-2').appendChild(input);
        }
    </script>
</x-app-layout>


