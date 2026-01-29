<div class="row">
    <div class="col-md-9">

        <legend class="legend-sm bg-light text-secondary px-3 py-2 rounded">
            General Info
        </legend>

        <div class="row">
            <div class="col-md-12">

                <!-- Role Info -->
                <table class="table table-bordered table-striped mb-4">
                    <tbody>
                    <tr>
                        <td width="160px">Role</td>
                        <td>{{ $role->name }}</td>
                    </tr>
                    </tbody>
                </table>

                <!-- Permissions Table -->
                <table class="table table-bordered table-hover bg-white shadow-sm">
                    <thead class="table-light">
                    <tr>
                        <th width="200">Group</th>
                        <th>Available Permissions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($permissions as $group => $groupPermissions)
                        <tr>
                            <td class="fw-semibold text-capitalize">
                                {{ str_replace('_', ' ', $group) }}
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($groupPermissions as $permission)
                                        <span class="badge bg-primary">
                                                {{ str_replace('.', ' ', $permission->name) }}
                                            </span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">
                                No permissions assigned to this role
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
