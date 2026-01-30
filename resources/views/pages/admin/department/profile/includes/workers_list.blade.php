<div class="table-responsive">
    <table class="table table-hover align-middle border-top">
        <thead class="table-light">
            <tr>
                <th width="50">#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Roles</th>
                <th class="text-center">Status</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($department->users as $index => $worker)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $worker->name }}</td>
                <td>{{ $worker->email }}</td>
                <td><span class="text-muted">{{ $worker->username ?? 'N/A' }}</span></td>
                <td>
                    @foreach($worker->roles as $role)
                        <span class="badge bg-soft-info text-info border border-info px-2">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </td>
                <td class="text-center">
                    @if($worker->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('admin_panel.users.profile', $worker->uuid) }}" class="btn btn-sm btn-outline-primary shadow-sm" title="View Profile">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <i class="fas fa-users-slash d-block mb-2 fs-3"></i>
                    No workers assigned to this department yet.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
