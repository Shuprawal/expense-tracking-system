<x-app-layout>
<div class="container m-4 p-2">
    <h3>List of categories used</h3>

    @foreach($categories as $category)
        <li><a href="{{route('categories.show',$category->id)}}">{{$category->name}}</a></li>

        <form action="{{route('categories.destroy',$category->id)}}" method="POST">
            @csrf
            @method('delete')
            <button class="btn btn-danger" type="submit">Delete</button>
        </form>


    @endforeach
</div>
</x-app-layout>
