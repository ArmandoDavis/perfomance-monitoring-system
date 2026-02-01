@extends('layouts.admin.app')
@section('title', __('Edit Expense'))

@include('includes.assets.select2_assets')
@include('includes.assets.ckeditor5_assets')
@include('includes.assets.maskmoney_assets')

@section('content')
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card radius-10">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">{{ __('Edit Expense Details') }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin_panel.expenses.update', $expense->uuid) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action_type" value="3">
                        <input type="hidden" name="resource_id" value="{{ $expense->id }}">

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('Related Task') }}</label>
                                <select name="task_id" class="form-select select2" required>
                                    @foreach($tasks as $task)
                                        <option value="{{ $task->id }}" {{ $expense->task_id == $task->id ? 'selected' : '' }}>
                                            {{ $task->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Amount --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('Amount (TZS)') }}</label>
                                <input type="text" name="amount" class="form-control" value="{{ old('amount', number_2_format($expense->amount)) }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('Description / Purpose') }}</label>
                                <textarea name="description" class="form-control ckeditor" rows="3">{{ old('description', $expense->description) }}</textarea>
                            </div>


                            <div class="d-flex justify-content-end gap-2">
                                <a href="javascript:history.back()" class="btn btn-light">
                                    {{ __('Cancel') }}
                                </a>

                                <button type="submit" class="btn btn-primary" id="submit_btn">{{ __('Update Expense') }}
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
