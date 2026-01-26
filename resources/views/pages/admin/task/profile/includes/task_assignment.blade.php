<div class="card-body p-0">
    <table class="table table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th>{{ __('User') }}</th>
                <th>{{ __('Assigned Budget') }}</th>
                <th>{{ __('Spent Amount') }}</th>
                <th>{{ __('Remaining Budget') }}</th>
                <th>{{ __('Active?') }}</th>
                @if($task->status->reference == "SCS004")
                    <th>{{ __('Actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($task->assignments as $assignment)
                <tr>
                    <td>{{ optional($assignment->user)->name }}</td>
                    <td>{{ number_2_format($assignment->assigned_budget) }}</td>
                    <td>{{ number_2_format($assignment->spent_amount) }}</td>
                    <td>{{ number_2_format($assignment->remaining_budget) }}</td>
                    <td>
                        @if($assignment->is_active)
                            <span class="badge bg-primary">{{ __('Yes') }}</span>
                        @else
                            <span class="badge bg-danger">{{ __('No') }}</span>
                        @endif
                    </td>
                    @if($task->status->reference == "SCS004")
                        <td class="text-nowrap">
                            @can('task.update')
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAssignmentModal-{{ $assignment->id }}">
                                    {{ __('Edit') }}
                                </button>
                            @endcan

                            @can('task.assign')
                                    @if($assignment->is_archived)
                                        <form action="{{ route('admin_panel.tasks.assignment.delete', $assignment->uuid) }}" method="POST" class="d-none confirm-form-remove-{{ $assignment->uuid }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger mb-2 me-2" onclick="formActionConfirmation('remove-{{ $assignment->uuid }}', '{{ __('Remove assignment') }}' )">
                                            <i class="material-icons-outlined">delete</i>
                                            {{ __('Remove') }}
                                        </a>
                                    @endif
                            @endcan
                    @endif
                    </td>
                </tr>


                <div class="modal fade" id="editAssignmentModal-{{ $assignment->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin_panel.tasks.assignment.update', $assignment->uuid) }}" name="submit_assignment">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        {{ __('Edit Assignment') }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="action_type" value="2">
                                    <input type="hidden" name="resource_id" value="{{ $assignment->id }}">

                                    {{-- User (readonly) --}}
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('User') }}</label>
                                        <input type="text" class="form-control" value="{{ $assignment->user->name }}" disabled>
                                    </div>

                                    {{-- Assigned Budget --}}
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Assigned Budget') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="assigned_budget" class="form-control money" value="{{ number_2_format($assignment->assigned_budget) }}" required>
                                    </div>

                                    {{-- Status --}}
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            {{ $assignment->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ __('Active') }}
                                        </label>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        {{ __('Cancel') }}
                                    </button>

                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="6" class="text-center">{{ __('No assignments yet') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
