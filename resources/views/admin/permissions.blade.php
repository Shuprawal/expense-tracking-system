<x-app-layout>
    <div class="container m-4 p-2">
        <form action="{{ route('permissions.store') }}" method="post">
            @csrf
            <div class="mb-4">
                <input type="checkbox" id="select-all" class="mr-2">
                <label for="select-all" class="font-bold">Select All Permissions</label>
                <button type="submit" class="btn btn-primary mt-4 m-3">Save Permissions</button>
            </div>


            @foreach($permissions as $group => $groupPermissions)
                <div class="border p-3 mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <input type="checkbox" id="group-{{ $group }}" class="group-checkbox mr-2">
                        <label for="group-{{ $group }}" class="font-bold text-lg">{{ $group }} Permissions</label>
                    </div>

                    @foreach($groupPermissions as $permission)
                        <div class="p-2 border-b bg-white">
                            <h3 class="text-md font-semibold">{{ $permission->slug }}</h3>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($roles as $role)
                                    <div class="d-flex align-items-center gap-3 space-x-2">
                                        <input type="checkbox"
                                               name="permissions[{{ $role->id }}][]"
                                               value="{{ $permission->id }}"
                                               class="role-permission-checkbox group-{{ $group }}"
                                            {{ $role->hasPermission($permission->name) ? 'checked' : '' }}>
                                        <span class="font-medium">{{ $role->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

        </form>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function () {
            document.querySelectorAll(['.role-permission-checkbox', '.group-checkbox']).forEach(checkbox => {
                checkbox.checked = this.checked;

            });
        });

        document.querySelectorAll('.group-checkbox').forEach(groupCheckbox => {
            groupCheckbox.addEventListener('change', function () {
                let groupClass = this.id;
                document.querySelectorAll('.' + groupClass).forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });
    </script>
</x-app-layout>
