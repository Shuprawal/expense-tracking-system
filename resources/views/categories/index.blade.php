<x-app-layout>
    <div class="container mt-5 p-4 border rounded shadow bg-white" style="max-width: 800px;">
        <h2 class="mb-4 text-center">Categories</h2>
        <x-search :route="'categories.index'" />


        <table class="table table-bordered">
            <thead class="table-dark">
            <tr>
                <th>Category Name</th>
                <th>Created By</th>
                <th>User count</th>
                <th>Action</th>
                <th>Disable</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->user->username}}</td>
                    <td>{{ $category->users_count}}</td>
                    <td>
                        <x-delete-button :route="'categories.destroy'" :parameters="$category->id"/>
                    </td>
                    <td>
                        <form action="{{route('categories.disable',$category->id)}}" method="post">
                            @csrf
                            @method('PUT')
                            @if($category->isDisabled())
                                <button type="submit" name="disable" class="btn btn-outline-success" value="No">Enable</button>
                            @else
                                <button type="submit" class="btn btn-outline-dark" name="disable" value="Yes">Disable</button>
                            @endif

{{--                            <button type="submit" name="disable" value="Yes">Yes</button>--}}

                        </form>
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
