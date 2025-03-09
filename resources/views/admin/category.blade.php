<x-app-layout>
<div class="container m-4 p-2">
    <h3>List of categories used</h3>

    @foreach($categories as $category)
        <li><a href="{{route('categories.show',$category->id)}}">{{$category->name}}</a></li>
    @endforeach
</div>
</x-app-layout>
