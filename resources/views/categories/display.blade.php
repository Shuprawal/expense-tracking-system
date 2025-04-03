<x-app-layout>
    @if(session('confirm_transfer'))
        <div class="alert alert-warning">
            <p>{{ session('confirm_transfer.message') }}</p>
            <form action="{{ route('categories.transfer') }}" method="post">
                @csrf
                <input type="hidden" name="old_category" value="{{ session('confirm_transfer.old_category_id') }}">
                <input type="hidden" name="new_category" value="{{ session('confirm_transfer.new_category_id') }}">
                <button class="btn btn-success" name="transfer" value="yes">Yes, transfer</button>
                <button class="btn btn-danger" name="transfer" value="no">No, don't transfer</button>
            </form>
        </div>
    @endif

    <div class="container m-2 p-2">

        <table class="table table-bordered">
            <thead class="table-dark">
            <tr>
                <th>Category Name</th>
                <th>Edit</th>
                <th>Delete</th>

            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td class="d-flex align-items-center gap-3">
                        <a href="{{route('categories.edit',$category->id)}}">edit</a>
                    </td>
                    <td>
                        <x-delete-button :route="'categories.destroy'" :parameters="$category->id" />
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{$categories->links()}}
        </div>
    </div>
</x-app-layout>
