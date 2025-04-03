<x-app-layout>
    <div class="container">
        <form action="{{route('categories.update',$category->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">EditCategory</label>
                <div id="new-category-container">
                    <input type="text" value="{{$category->name}}" class="form-control" name="name" placeholder="Enter Category Name">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                <button type="submit" class="btn btn-success btn-sm mt-2"> Edit</button>

            </div>
        </form>

    </div>
</x-app-layout>
