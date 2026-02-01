@extends('layouts.hod.app')
@section('title', __('Record New Expense'))

@include('includes.assets.ckeditor5_assets')
@include('includes.assets.select2_assets')
@include('includes.assets.maskmoney_assets')

@section('content')
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card radius-10">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold">{{ __('Record New Expense') }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('hod_panel.expenses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action_type" value="3">

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('Select Related Task') }} <span class="text-danger">*</span></label>
                                <select name="task_id" class="form-select select2 @error('task_id') is-invalid @enderror" required>
                                    <option selected hidden disabled>{{ __('Choose Task') }}</option>
                                    @foreach($tasks as $task)
                                        <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>
                                            {{ $task->title }} (Budget: TZS {{ number_format($task->allocated_budget) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('task_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('Amount (TZS)') }} <span class="text-danger">*</span></label>
                                <input type="text" name="amount" class="form-control money @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                                @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('Attach Receipt (Optional)') }}</label>
                                <input type="file" name="receipt" class="form-control @error('receipt') is-invalid @enderror" accept=".jpg,.png,.pdf">
                                <small class="text-muted">Max: 2MB (PDF, JPG, PNG)</small>
                                @error('receipt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('Description / Purpose') }} <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control ckeditor @error('description') is-invalid @enderror"
                                          rows="3" placeholder="{{ __('Describe what this expense was for...') }}">{{ old('description') }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>


                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('hod_panel.expenses.index') }}" class="btn btn-light">
                                    {{ __('Cancel') }}
                                </a>

                                <button type="submit" class="btn btn-primary" id="submit_btn">{{ __('Save Expense') }}
                                    <label id="submit_label"></label>
                                </button>
                            </div>
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

        pleaseWaitSubmitButton("submit_btn", "submit_label", "{{ trans('Please wait') }}", 2);
        $('body').on('submit', 'form[name=create]', function () {
            pleaseWaitSubmitButton("submit_btn", "submit_label", "{{ trans('Please wait') }}", 1);
        });
    </script>
@endpush
