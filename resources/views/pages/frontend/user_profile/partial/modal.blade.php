<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    {{ __('Change Password') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('frontend.user_profile.change_password') }}">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            {{ __('Current Password') }}
                        </label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="{{ __('Enter current password') }}" required>
                        @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            {{ __('New Password') }}
                        </label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Enter new password') }}" required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            {{ __('Confirm New Password') }}
                        </label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm new password') }}" required>
                    </div>

                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ __('Your password must be at least 8 characters and contain a mix of letters and numbers.') }}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-lock me-1"></i>
                        {{ __('Update Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- update details --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">
                    {{ __('Edit Profile') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('frontend.user_profile.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">

                        {{-- Full Name --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('Full Name') }}
                            </label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('Email Address') }}
                            </label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('Phone Number') }}
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Department --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('Department') }}
                            </label>
                            <input type="text" value="{{ optional(auth()->user()->department)->name }}" class="form-control" disabled>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
