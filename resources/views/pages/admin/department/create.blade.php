@extends('layouts.admin.app')
@section('title', __('Edit Department'))
@include('includes.assets.validate_assets')

@section('content')
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Edit Department') }}</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin_panel.departments.update', $department->uuid) }}" name="update">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action_type" value="2">
                    <input type="hidden" name="resource_id" value="{{ $department->id }}">

                    {{-- Department Name --}}
                    <div class="mb-3">
                        <label for="department_name" class="form-label">{{ __('Department Name') }}<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="department_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $department->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Abbreviation --}}
                    <div class="mb-3">
                        <label for="abbreviation" class="form-label">{{ __('Abbreviation') }}</label>
                        <input type="text" name="abbreviation" id="abbreviation" class="form-control @error('abbreviation') is-invalid @enderror" value="{{ old('abbreviation', $department->abbreviation) }}">
                        @error('abbreviation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label for="is_active" class="form-label">{{ __('Status') }}</label>
                        <select name="is_active" id="is_active" class="form-select select2 @error('is_active') is-invalid @enderror">
                            <option value="1" {{ $department->is_active ? 'selected' : '' }}>
                                {{ __('Active') }}
                            </option>
                            <option value="0" {{ !$department->is_active ? 'selected' : '' }}>
                                {{ __('Inactive') }}
                            </option>
                        </select>

                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin_panel.departments.index') }}" class="btn btn-light">
                            {{ __('Cancel') }}
                        </a>

                        <button type="submit" class="btn btn-primary" id="submit_btn">
                            <span id="submit_label">{{ __('Update') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        pleaseWaitSubmitButton("submit_btn", "submit_label", "{{ trans('Please wait') }}", 2);

        // Prevent double-submit
        $('body').on('submit', 'form[name=update]', function(e) {
            pleaseWaitSubmitButton("submit_btn","submit_label","{{ trans('Please wait') }}",1);
        });
    </script>
@endpush
