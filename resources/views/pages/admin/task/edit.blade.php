@extends('layouts.admin.app')
@section('title', __('Update Task'))
@include('includes.assets.validate_assets')

@section('content')
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Update Task') }}</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin_panel.task.update', $task->uuid) }}" name="update">
                    @csrf
                    @method("PUT")
                    <input type="hidden" name="action_type" value="2">
                    <input type="hidden" name="resource_id" value="{{ $task->id }}">

                    {{-- Department Name --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('title') }}<span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $task->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            {{ __('Description') }}
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="4"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Department --}}
                    <div class="mb-3">
                        <label for="department_id" class="form-label">
                            {{ __('Department') }} <span class="text-danger">*</span>
                        </label>
                        <select name="department_id"
                                id="department_id"
                                class="form-select @error('department_id') is-invalid @enderror"
                                required>
                            <option value="">{{ __('Select Department') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $task->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Allocated Budget --}}
                    <div class="mb-3">
                        <label for="allocated_budget" class="form-label">
                            {{ __('Allocated Budget') }}
                        </label>
                        <input type="number"
                               step="0.01"
                               name="allocated_budget"
                               id="allocated_budget"
                               value="{{ old('allocated_budget', $task->allocated_budget) }}"
                               class="form-control @error('allocated_budget') is-invalid @enderror">
                        @error('allocated_budget')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="status_cv_id" class="form-label">
                            {{ __('Status') }} <span class="text-danger">*</span>
                        </label>
                        <select name="status_cv_id"
                                id="status_cv_id"
                                class="form-select @error('status_cv_id') is-invalid @enderror"
                                required>
                            <option value="">{{ __('Select Status') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}"
                                    {{ old('status_cv_id', $task->status_cv_id) == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_cv_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Dates --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">
                                {{ __('Start Date') }}
                            </label>
                            <input type="date"
                                   name="start_date"
                                   id="start_date"
                                   value="{{ old('start_date', $task->start_date?->format('Y-m-d')) }}"
                                   class="form-control @error('start_date') is-invalid @enderror">
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">
                                {{ __('End Date') }}
                            </label>
                            <input type="date"
                                   name="end_date"
                                   id="end_date"
                                   value="{{ old('end_date', $task->end_date?->format('Y-m-d')) }}"
                                   class="form-control @error('end_date') is-invalid @enderror">
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin_panel.tasks.profile', $task->uuid) }}" class="btn btn-light">
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
