@extends('layouts.admin.app')

@section('title', 'Edit Staff')

@include('includes.assets.select2_assets')
@include('includes.assets.validate_assets')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Edit Staff</h5>
                </div>

                <div class="card-body">
                    <form method="POST"
                          action="{{ route('admin_panel.users.update', $user->uuid) }}"
                          id="update"
                          name="edit"
                          class="needs-validation"
                          novalidate
                          enctype="multipart/form-data"
                          autocomplete="off">

                        @csrf
                        @method('PUT')

                        <input type="hidden" name="resource_id" value="{{ $user->id }}">
                        <input type="hidden" name="action_type" value="2">

                        <div class="row mb-3">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control form-control-sm @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}"
                                       required>

                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    Phone <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="phone"
                                       id="phone"
                                       class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}"
                                       required>

                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control form-control-sm @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       required>

                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div class="col-md-6">
                                <label for="username" class="form-label">
                                    Username
                                </label>
                                <input type="text" name="username" id="username" class="form-control form-control-sm @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="row mb-3">
                            <div class="col-md-6 ">
                                <label for="roles" class="form-label">
                                    Roles <span class="text-danger">*</span>
                                </label>

                                <select name="roles[]" id="roles" class="form-select select2 @error('roles') is-invalid @enderror" multiple required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}"{{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $role->display_name ?? $role->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                        {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>

                                    @error('is_active')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin_panel.users.profile', $user->uuid) }}" class="btn btn-dark">Cancel</a>

                            <button type="submit" class="btn btn-primary" id="submit_btn">
                                Update
                            </button>
                            <label id="submit_label" class="ms-2"></label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(".select2").select2();

        pleaseWaitSubmitButton("submit_btn", "submit_label", "Please wait", 2);

        $('body').on('submit', 'form[name=edit]', function () {
            pleaseWaitSubmitButton("submit_btn", "submit_label", "Please wait", 1);
        });
    </script>
@endpush
