@extends('layouts.hod.app')
@section('title', __('Create Task'))
@include('includes.assets.validate_assets')
@include('includes.assets.ckeditor5_assets')
@include('includes.assets.select2_assets')
@include('includes.assets.maskmoney_assets')
@include('includes.assets.datetimepicker')

@section('content')
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Create New Task') }}</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('hod_panel.tasks.store') }}" name="create">
                    @csrf
                    <input type="hidden" name="action_type" value="1">

                    {{-- Department Name --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('title') }}<span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="4" class="form-control ckeditor @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Department --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="mb-3">
                                <label for="department_id" class="form-label">
                                    {{ __('Department') }} <span class="text-danger">*</span>
                                </label>
                                <select name="department_id" id="department_id" class="form-select select2 @error('department_id') is-invalid @enderror" required>
                                    <option value="">{{ __('Select Department') }}</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            {{-- Allocated Budget --}}
                            <div class="mb-3">
                                <label for="allocated_budget" class="form-label">
                                    {{ __('Allocated Budget') }}
                                </label>
                                <input type="text" name="allocated_budget" id="allocated_budget" value="{{ old('allocated_budget') }}" class="form-control money @error('allocated_budget') is-invalid @enderror">
                                @error('allocated_budget')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="mb-3">
                                <label for="user_ids" class="form-label">{{ __('Assign users') }}</label>
                                <select name="user_ids[]" id="user_ids" class="form-select select2 @error('user_ids') is-invalid @enderror" multiple>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_ids') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            {{-- Status --}}
                            <div class="mb-3">
                                <label for="status_cv_id" class="form-label">{{ __('Status') }} </label>
                                <select name="status_cv_id" id="status_cv_id" class="form-select select2 @error('status_cv_id') is-invalid @enderror">
                                    <option selected disabled hidden>{{ __('Select Status') }}</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}"
                                            {{ old('status_cv_id') == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status_cv_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                            <input type="text" name="start_date" id="start_date" value="{{ old('start_date') }}" class="form-control datepicker2 @error('start_date') is-invalid @enderror" autocomplete="off">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">{{ __('End Date') }} <span class="text-danger">*</span></label>
                            <input type="text" name="end_date" id="end_date" value="{{ old('end_date') }}" class="form-control datepicker_after_today @error('end_date') is-invalid @enderror" autocomplete="off">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('hod_panel.tasks.index') }}" class="btn btn-light">
                            {{ __('Cancel') }}
                        </a>

                        <button type="submit" class="btn btn-primary" id="submit_btn">{{ __('Create') }}
                            <label id="submit_label"></label>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(".select2").select2();

        pleaseWaitSubmitButton("submit_btn", "submit_label", "{{ trans('Please wait') }}", 2);
        $('body').on('submit', 'form[name=create]', function(e) {
            pleaseWaitSubmitButton("submit_btn","submit_label","{{ trans('Please wait') }}",1);
        });
    </script>
@endpush
