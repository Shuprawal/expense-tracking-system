<x-app-layout>
    <div class="container m-4 p-2">
        @foreach($permissions as $group => $groupPermissions)
            <div class="border p-3 mb-3">
                <h2 class="font-bold text-lg">{{ ($group) }} Permissions</h2>

                @foreach($groupPermissions as $permission)
                    <div class="p-2 border-b">
                        <h3 class="text-md font-semibold">{{ $permission->slug }}</h3>

                        @foreach($roles as $role)
                            <div class="flex items-center space-x-2">
                                <span class="font-medium">{{ ($role->name) }} Role:</span>

                                @if($role->hasPermission($permission->name))

                                    <a class="text-red-500 hover:underline"
                                       href="{{ route('removePermission', ['role' => $role->id, 'permission' => $permission->id]) }}">
                                        Remove
                                    </a>
                                @else
                                    <a class="text-green-500 hover:underline"
                                       href="{{ route('addPermission', ['role' => $role->id, 'permission' => $permission->id]) }}">
                                        Add
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</x-app-layout>
