<x-app-layout>
    <div class="container">
        <div class="p-2">
        <a href="{{route('roles.create')}}" class="btn btn-primary">Add</a>

        </div>
        <div class="card">

            @forelse($roles as $role)
                <li class="m-2"><a href="{{route('roles.show',$role->id)}}">{{$role->name}} </a></li>
            @empty
                <li class="m-2"><a>No role</a></li>
            @endforelse
            <h2></h2>
        </div>
    </div>
</x-app-layout>
