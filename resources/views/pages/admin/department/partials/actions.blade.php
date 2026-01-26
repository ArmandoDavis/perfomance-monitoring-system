<div class="btn-group" role="group">
    @can('update', $department)
        <a href="{{ route('admin_panel.departments.edit', $department->uuid) }}" class="btn btn-sm btn-primary">
            {{ __('Edit') }} <i class="bi bi-pencil"></i>
        </a>
    @endcan

    @can('update', $department)
        @if ($department->is_active)
            {{-- Deactivate Form --}}
            <form method="POST" action="{{ route('admin_panel.departments.change_status', $department->uuid) }}" class="d-none confirm-form-deactivate-{{ $department->uuid }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="action_type" value="3">
                <input type="hidden" name="action" value="deactivate">
            </form>

            <button type="button" class="btn btn-sm btn-warning text-white" onclick="formActionConfirmation('deactivate-{{ $department->uuid }}', '{{ __('Deactivate') }}' )">
                <i class="bi bi-slash-circle"></i> {{ __('Deactivate') }}
            </button>

        @else
            {{-- Activate Form --}}
            <form method="POST" action="{{ route('admin_panel.departments.change_status', $department->uuid) }}" class="d-none confirm-form-activate-{{ $department->uuid }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="action_type" value="3">
                <input type="hidden" name="action" value="activate">
            </form>

            <button type="button" class="btn btn-sm btn-success" onclick="formActionConfirmation('activate-{{ $department->uuid }}','{{ __('Activate') }}'  )">
                <i class="bi bi-check-circle"></i> {{ __('Activate') }}
            </button>
        @endif
    @endcan
</div>
