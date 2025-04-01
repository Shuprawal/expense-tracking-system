<x-app-layout>
    <div class="container m-2 p-2">
        <div class="card">
            <div class="card-header bg-white border-0 shadow-sm">
                <h3>Add New Role</h3>
                <form action="{{route('roles.store')}}" method="post">
                    @csrf
                    <label for="name">Name</label>
                    <input type="text" class="name" name="name">
                    <button class="btn btn-primary" type="submit">Add</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
