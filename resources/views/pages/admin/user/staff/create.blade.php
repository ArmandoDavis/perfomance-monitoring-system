@extends('layouts.admin.app')
@section('title', 'Edit staff')

@include('includes.assets.select2_assets')

@section('content')
    <form action="{{ route('admin_panel.users.store') }}" method="POST" enctype="multipart/form-data" name="create" class="needs-validation" novalidate autocomplete="off">
        @csrf
        <input type="hidden" name="action_type" value="1">

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">

                            <!-- Name & Phone -->
                            <div class="row g-3 mb-2">
                                <div class="col-xxl-3 col-md-6">
                                    <label for="name" class="form-label required_asterik">
                                        Name
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control form-control-sm @error('name') is-invalid @enderror" autocomplete="off">

                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-xxl-3 col-md-6">
                                    <label for="phone" class="form-label required_asterik">
                                        Phone
                                    </label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control form-control-sm @error('phone') is-invalid @enderror" autocomplete="off">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email & Username -->
                            <div class="row g-3 mb-2">
                                <div class="col-xxl-3 col-md-6">
                                    <label for="email" class="form-label required_asterik">
                                        Email
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-sm @error('email') is-invalid @enderror" autocomplete="off">

                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-xxl-3 col-md-6">
                                    <label for="username" class="form-label">
                                        Username
                                    </label>
                                    <input type="text"
                                           name="username"
                                           id="username"
                                           value="{{ old('username') }}"
                                           class="form-control form-control-sm @error('username') is-invalid @enderror"
                                           autocomplete="off">

                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Roles -->
                            <div class="row g-3 mb-3">
                                <div class="col-xxl-3 col-md-6">
                                    <label for="department_id" class="form-label required_asterik">
                                        Department
                                    </label>

                                    <select name="department_id"
                                            id="department_id"
                                            class="select2 form-control @error('department_id') is-invalid @enderror">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('department_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-xxl-3 col-md-6">
                                    <label for="roles" class="form-label required_asterik">
                                        Role
                                    </label>

                                    <select name="roles[]"
                                            id="roles"
                                            class="select2 form-control @error('roles') is-invalid @enderror"
                                            multiple>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('roles')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                            {{ old('is_active', 1) ? 'checked' : '' }}>

                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin_panel.users.index') }}"
                                           id="cancel"
                                           class="btn btn-outline-secondary">
                                            Cancel
                                        </a>

                                        <button type="submit"
                                                id="submit_btn"
                                                class="btn btn-primary">
                                            Submit
                                        </button>

                                        <span id="submit_label" class="ms-2 small text-muted"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        pleaseWaitSubmitButton("submit_btn", "submit_label", "Please wait...", 2);

        $('body').on('submit', 'form[name=create]', function(e) {
            e.preventDefault();
            pleaseWaitSubmitButton("submit_btn", "submit_label", "Please wait...", 1);
            this.submit();
        });

        $(".select2").select2({
            width: '100%',
            placeholder: "Select roles"
        });
    </script>
@endpush
