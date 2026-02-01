<div class="table-responsive">
    <table class="table table-sm table-borderless align-middle">
        <thead class="table-light">
        <tr>
            <th>{{ __('Recipient') }}</th>
            <th>{{ __('Shared By') }}</th>
            <th>{{ __('Date') }}</th>
            <th class="text-end">{{ __('Action') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($task->shares as $share)
            <tr>
                <td>
                    @if($share->shared_with_user_id)
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                    {{ substr($share->sharedWithUser->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0 fs-13">{{ $share->sharedWithUser->name }}</h6>
                                <small class="text-muted">{{ $share->sharedWith->email ?? ''}}</small>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                    {{ substr($share->sharedWithDepartment->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0 fs-13">{{ $share->sharedWithDepartment->name }}</h6>
                                <small class="text-muted">{{ $share->sharedWithDepartment->email }}</small>
                            </div>
                        </div>
                    @endif
                </td>
                <td>{{ $share->sharedBy->name ?? 'System' }}</td>
                <td>{{ short_date_format($share->created_at) }}</td>
                <td class="text-end">
                    @can('share', $task)
                        <form action="{{ route('frontend.tasks.share.delete', $share->uuid) }}" method="POST" class="confirm-form-remove-share-{{ $share->uuid }} d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-link text-danger p-0"
                                    onclick="formActionConfirmation('remove-share-{{ $share->uuid }}', '{{ __('Remove Share') }}')">
                                {{ __('Remove ') }} <i class="fas fa-user-minus"></i>
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-3">
                    <i class="fas fa-info-circle me-1"></i> {{ __('No one else was involved in this work.') }}
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
