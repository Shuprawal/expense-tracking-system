<x-app-layout>

    <div class="container m-4">
        <div class="card-header d-flex align-items-center gap-4">
            <h1>Role name: {{ $role->name }}</h1>
            <a href="{{ route('permissions.index', ['role_id' => $role->id]) }}" class="btn btn-primary">Permission</a>
        </div>

        <h3>User List</h3>

        <div class="card row gap-3">
            <x-search :route="'roles.show'" :parameters="['role'=>$role->id]"/>
            @forelse($users as $user)

                <div class="d-flex gap-3 align-items-center m-3">
                    <h2>{{ $user->username }}</h2>
                    @if($user->roles->contains($role))

                        <form action="{{ route('role.detach', ['user' => $user->id, 'role' => $role->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    @else

                        <form action="{{ route('role.attach')}}" method="POST" >
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    @endif
                </div>
            @empty
                <h2>No users found</h2>
            @endforelse
        </div>
        <div class="mt-3">
            {{$users->appends(['inputText'=>request('inputText')])->links()}}
        </div>

    </div>
</x-app-layout>
