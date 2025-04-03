<x-app-layout>
    <div class="container mt-5 p-4 border rounded shadow bg-white" style="max-width: 800px;">
        <h2 class="mb-4 text-center">Categories</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
            <tr>
                <th>Category Name</th>
                <th>Created By</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->user->username}}</td>
                    <td><form action="{{route('categories.destroy',$category->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
