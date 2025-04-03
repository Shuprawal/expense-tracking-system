
<x-app-layout>
    <div class="container mx-auto my-6 p-4 bg-gray-50 rounded-lg shadow-md">
        <x-search :route="'permissions.search'" :parameters="$roles->pluck('id')->toArray()"></x-search>

        <form action="{{ route('permissions.store') }}" method="post" class="space-y-6">
            @csrf
            <div class="flex items-center justify-between bg-white p-4 rounded-md shadow-sm">
                <div class="flex items-center gap-4">
                    <input type="checkbox" id="select-all" class="mr-2 h-5 w-5 text-indigo-600">
                    <label for="select-all" class="font-bold text-gray-800 text-lg">Select All Permissions</label>
                    <select id="role-selector" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-700">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Permissions</button>
            </div>

            @foreach($permissions as $group => $groupPermissions)
                <div class="border border-gray-200 p-4 rounded-md bg-white shadow-sm hover:shadow-md transition duration-200">
                    <div class="flex items-center gap-3 mb-4">
                        <input type="checkbox" id="group-{{ $group }}" class="group-checkbox h-5 w-5 text-indigo-600">
                        <label for="group-{{ $group }}" class="font-bold text-xl text-gray-800">{{ $group }} Permissions</label>
                    </div>

                    @foreach($groupPermissions as $permission)
                        <div class="p-3 border-b border-gray-100 bg-white last:border-b-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-md font-semibold text-gray-700">{{ $permission->slug }}</h3>
                                <div class="flex items-center gap-3">
                                    <input type="checkbox"
                                           name="permissions[selected_role][{{ $permission->id }}]"
                                           value="{{ $permission->id }}"
                                           class="role-permission-checkbox group-{{ $group }} h-5 w-5 text-indigo-600"
                                           data-permission-id="{{ $permission->id }}"
                                           disabled>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </form>
    </div>

    <script>
        const roleSelector = document.getElementById('role-selector');
        const permissionCheckboxes = document.querySelectorAll('.role-permission-checkbox');
        let rolePermissions = @json($roles->mapWithKeys(function ($role) {
            return [$role->id => $role->permissions->pluck('id')->toArray()];
        })->toArray());

        roleSelector.addEventListener('change', function () {
            const selectedRoleId = this.value;
            permissionCheckboxes.forEach(checkbox => {
                if (selectedRoleId) {
                    checkbox.disabled = false;
                    checkbox.name = `permissions[${selectedRoleId}][${checkbox.dataset.permissionId}]`;
                    checkbox.checked = rolePermissions[selectedRoleId]?.includes(parseInt(checkbox.value)) || false;
                } else {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                }
            });
        });

        document.getElementById('select-all').addEventListener('change', function () {
            document.querySelectorAll(['.role-permission-checkbox', '.group-checkbox']).forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = this.checked;
                }
            });
        });

        document.querySelectorAll('.group-checkbox').forEach(groupCheckbox => {
            groupCheckbox.addEventListener('change', function () {
                let groupClass = this.id;
                document.querySelectorAll('.' + groupClass).forEach(checkbox => {
                    if (!checkbox.disabled) {
                        checkbox.checked = this.checked;
                    }
                });
            });
        });
    </script>
</x-app-layout>
