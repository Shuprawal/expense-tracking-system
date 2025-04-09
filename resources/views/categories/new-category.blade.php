<x-app-layout>

    <div class="container mt-5 p-4 border rounded shadow bg-white" style="max-width: 600px;">
        <h2 class="mb-4 text-center">Select or Add Categories</h2>


{{--        @if ($errors->any())--}}
{{--            <div class="alert alert-danger">--}}
{{--                <ul class="mb-0">--}}
{{--                    @foreach ($errors->all() as $error)--}}
{{--                        <li>{{ $error }}</li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--        --}}


        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Choose Existing Categories</label>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="categories[]"
                                   value="{{ $category->name }}"
                                   id="category_{{ $category->id }}"
                                {{ in_array($category->name, old('categories', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="category_{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('categories')" class="mt-2" />
            </div>


            <div class="mb-3">
                <label class="form-label">Add New Categories</label>
                <div id="new-category-container">
                    @php $oldNewCategories = old('new_categories', []); @endphp

                    @forelse ($oldNewCategories as $index => $value)
                        <input type="text" class="form-control mt-2" name="new_categories[]" value="{{ $value }}" placeholder="Enter Category Name">
                        @if ($errors->has("new_categories.$index"))
                            <div class="text-danger mt-1">{{ $errors->first("new_categories.$index") }}</div>
                        @endif
                    @empty
                        <input type="text" class="form-control" name="new_categories[]" placeholder="Enter Category Name">
                    @endforelse
                </div>

                <button type="button" class="btn btn-success btn-sm mt-2" onclick="addCategory()">+ Add Another</button>
                <x-input-error :messages="$errors->get('error1')" class="mt-2" />
            </div>


            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" value="{{ old('date') }}" class="form-control" id="date" name="date" required>
                <x-input-error :messages="$errors->get('date')" class="mt-2" />
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
