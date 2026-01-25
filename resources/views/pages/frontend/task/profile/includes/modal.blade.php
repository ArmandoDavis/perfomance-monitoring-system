{{-- Add Comment Modal --}}
<div class="modal fade" id="addCommentModal-{{ $task->uuid }}" tabindex="-1" aria-labelledby="addCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('frontend.tasks.comment.store', $task->uuid) }}" method="POST" name="submit_comment">
                @csrf
                <input type="hidden" name="action_type" value="1">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCommentModalLabel">{{ __('Add Comment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="comment_content" class="form-label">{{ __('Comment') }} <span class="text-danger">*</span></label>
                        <textarea name="content" id="comment_content" rows="4" class="form-control ckeditor @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="submit_btn">
                        <span >{{ __('Submit') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- addMyExpenseModal --}}
<div class="modal fade" id="addMyExpenseModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('frontend.tasks.expenses.store', $task->uuid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add Expense') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>{{ __('Amount') }} <span class="text-danger">*</span></label>
                        <input type="hidden" name="action_type" value="1">
                        <input type="text" name="amount" class="form-control money" required>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('Description') }}</label>
                        <textarea name="description" class="form-control ckeditor_basic"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('Receipt') }}</label>
                        <input type="file" name="receipt" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
