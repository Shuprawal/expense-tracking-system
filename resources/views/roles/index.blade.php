<x-app-layout>
    <div class="container">
        <div class="p-2">
        <a href="{{route('roles.create')}}" class="btn btn-primary">Add</a>

        </div>
        <div class="card">

            <x-search :route="'roles.index'"/>
            @forelse($roles as $role)
                <div class="card-body p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    <ul class="space-y-2">
                        <li class="flex items-center justify-between m-0">
                            <a href="{{ route('roles.show', $role->id) }}"
                               class="text-indigo-600 dark:text-indigo-400 font-medium hover:text-indigo-800 dark:hover:text-indigo-300 transition duration-150">
                                {{ ucfirst($role->name) }}
                            </a>
                            <x-delete-button :route="'roles.destroy'" :parameters="$role" :title="ucfirst($role->name). ' Delete'"  :message="'Are you sure you want to delete this role?'"/>
                        </li>
                    </ul>
                </div>

            @empty
                <li class="m-2"><a>No role</a></li>
            @endforelse
            <h2></h2>
        </div>
    </div>
</x-app-layout>
