<x-app-layout>

    <div class="container m-4 p-4">
        <div class="card">
            <div class="card-body">
                <x-search :route="'users.index'"/>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Total categories count</th>
                            <th scope="col">Total expenses count</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
{{--                            <th scope="col">View</th>--}}

                        </tr>
                        </thead>
                        <tbody>

                        @foreach($users as $user)
                            <tr>
                                <td>{{ ucfirst($user->username) }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->categories_count }} </td>
                                <td>{{ $user->expenses_count }} </td>
                                <td>{{ ucfirst($user->roles->pluck('name')->implode(', ')) }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{route('users.show',$user->id)}}"><i class="bi bi-eye"></i> </a>
{{--                                        <a href="{{route('users.edit',$user->id)}}}"><i class="bi bi-pencil-fill"></i></a>--}}
                                        <x-delete-button :route="'users.destroy'" :parameters="$user->id"/>
                                    </div>


                                </td>
                            </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




</x-app-layout>
