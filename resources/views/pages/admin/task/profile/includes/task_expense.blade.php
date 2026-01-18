<div class="card-body">
    @if($task->expenses->count())
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Receipt') }}</th>
                    <th>{{ __('Approve Status') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($task->expenses as $expense)
                    <tr>
                        <td>{{ $expense->user->name }}</td>
                        <td>{{ number_2_format($expense->amount) }}</td>
                        <td>{{ Str::limit($expense->description, 40) }}</td>
                        <td>
                            @if($expense->receipt)
                                <a href="{{ route('attachments.download', $expense->receipt->uuid) }}" class="text-primary">
                                    <i class="material-icons-outlined">download</i>
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($expense->approved_at)
                                <span class="badge bg-success">{{ __('Approved') }}</span>
                            @else
                                <span class="badge bg-warning">{{ __('Pending') }}</span>
                            @endif
                        </td>
                        <td>{{ short_date_format_with_day($expense->created_at) }}</td>

                        @if(!$expense->approved_at)
                            <td>
                                <form action="{{ route('admin_panel.tasks.expenses.approve', $expense->uuid) }}" method="POST" class="d-none" id="confirm-form-approve-{{ $expense->uuid }}">
                                    @csrf
                                    @method('PUT')
                                </form>

                                <a href="javascript:void(0)" class="btn btn-sm btn-success mb-2 me-2" onclick="formActionConfirmation('approve-{{ $expense->uuid }}', '{{ __('approve') }}'  )">
                                    {{ __('approve') }}
                                </a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted mb-0">{{ __('No expenses recorded for this task.') }}</p>
    @endif
</div>
