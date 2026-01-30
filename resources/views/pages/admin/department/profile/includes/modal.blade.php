<div class="modal fade" id="uploadUpdatePasswordModal" tabindex="-1" aria-labelledby="uploadUpdatePasswordModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin_panel.users.update_password', $user->uuid) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="action_type" value="5">
                <input type="hidden" name="resource_id" value="{{ $user->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCommentModalLabel">{{ __('Update Password For:') }} {{ $user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" value="{{ old('password') }}" class="form-control form-control-sm @error('password') is-invalid @enderror" autocomplete="off">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">{{ __('Confirm password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="confirm_password" id="confirm_password" value="{{ old('confirm_password') }}" class="form-control form-control-sm @error('confirm_password') is-invalid @enderror" autocomplete="off">
                        @error('confirm_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="submit_btn">
                        {{ __('Update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
