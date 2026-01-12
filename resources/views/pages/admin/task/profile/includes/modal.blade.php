{{-- Add Comment Modal --}}
<div class="modal fade" id="addCommentModal-{{ $task->uuid }}" tabindex="-1" aria-labelledby="addCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin_panel.tasks.comment.store', $task->uuid) }}" method="POST" name="submit_comment">
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
                        <span id="submit_label">{{ __('Submit') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{--addAssignmentModal--}}
<div class="modal fade" id="addAssignmentModal" tabindex="-1" aria-labelledby="addAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin_panel.tasks.assignment.store', $task->uuid) }}" method="POST" name="submit_assignment">
                @csrf
                <input type="hidden" name="action_type" value="1">

                <div class="modal-header">
                    <h5 class="modal-title" id="addAssignmentModalLabel">{{ __('Assign User') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_ids" class="form-label">{{ __('User') }} <span class="text-danger">*</span></label>
                        <select name="user_ids[]" id="user_ids" multiple class="form-select select2" required>
                            <option value="">{{ __('Select User') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_budget" class="form-label">{{ __('Assigned Budget') }} <span class="text-danger">*</span></label>
                        <input type="text" name="assigned_budget" id="assigned_budget" class="form-control money" required>
                    </div>

                    <div class="mb-3">
                        <label for="is_active" class="form-label">{{ __('Active?') }}</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1" selected>{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

                    <button type="submit" class="btn btn-primary" id="submit_btn">
                        <span id="submit_label">{{ __('Assign') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Add Progress Modal --}}
<div class="modal fade" id="addProgressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin_panel.tasks.progress.store', $task->uuid) }}"
                  method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Task Progress') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="form-select select2" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Comment --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('Comment') }}</label>
                        <textarea name="comment" id="comment" rows="4" class="form-control ckeditor_basic @error('comment') is-invalid @enderror">{{ old('comment') }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button class="btn btn-primary">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="addPerformanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('admin_panel.tasks.performance.store', $task->uuid) }}">
            @csrf
            <input type="hidden" name="action_type" value="1">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Evaluate Performance') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- User --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('Staff') }} <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select select2" required>
                            <option value="">{{ __('Select Staff') }}</option>
                            @foreach($task->assignments as $assignment)
                                <option value="{{ $assignment->user->id }}">
                                    {{ $assignment->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        @foreach([
                            'timeliness_score' => [
                                'label' => 'Timeliness',
                                'desc'  => 'Was the task completed on time?',
                                'options' => [
                                    10 => 'Yes, on time',
                                    6  => 'Slightly late',
                                    2  => 'No, late',
                                ]
                            ],
                            'quality_score' => [
                                'label' => 'Work Quality',
                                'desc'  => 'How good is the quality of the work delivered?',
                                'options' => [
                                    10 => 'Excellent quality',
                                    6  => 'Acceptable quality',
                                    2  => 'Poor quality',
                                ]
                            ],
                            'budget_score' => [
                                'label' => 'Budget Control',
                                'desc'  => 'Was the task completed within the assigned budget?',
                                'options' => [
                                    10 => 'Within budget',
                                    6  => 'Slightly over budget',
                                    2  => 'Over budget',
                                ]
                            ],
                            'kpi_score' => [
                                'label' => 'Goal Achievement',
                                'desc'  => 'Did the task meet its intended goals?',
                                'options' => [
                                    10 => 'Fully achieved',
                                    6  => 'Partially achieved',
                                    2  => 'Not achieved',
                                ]
                            ],
                        ] as $field => $data)
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">{{ __($data['label']) }} <span class="text-danger">*</span></label>
                                <small class="text-muted d-block mb-1">
                                    {{ __($data['desc']) }}
                                </small>

                                <select name="{{ $field }}" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    @foreach($data['options'] as $value => $text)
                                        <option value="{{ $value }}">{{ __($text) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>


                    <div class="mb-3">
                        <label class="form-label">{{ __('Remarks') }}</label>
                        <textarea name="remarks" id="remarks" rows="4" class="form-control ckeditor_basic @error('remarks') is-invalid @enderror">{{ old('remarks') }}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button class="btn btn-primary">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
    <script>
        $('#addAssignmentModal').on('shown.bs.modal', function () {
            $('#user_ids').select2({
                placeholder: "{{ __('Select users') }}",
                dropdownParent: $('#addAssignmentModal'),
                allowClear: true,
                width: '100%'
            });
        });

        pleaseWaitSubmitButton("submit_btn", "submit_label", "{{ trans('Please wait') }}", 2);

        // Prevent double-submit
        $('body').on('submit', 'form[name=submit_comment]', function(e) {
            pleaseWaitSubmitButton("submit_btn","submit_label","{{ trans('Please wait') }}",1);
        });

        // Prevent double-submit
        $('body').on('submit', 'form[name=submit_assignment]', function(e) {
            pleaseWaitSubmitButton("submit_btn","submit_label","{{ trans('Please wait') }}",1);
        });
    </script>
@endpush
