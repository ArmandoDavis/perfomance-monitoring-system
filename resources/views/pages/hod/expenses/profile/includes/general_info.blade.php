<div class="row">
    <div class="col-md-7">
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-transparent text-dark fw-bold">
                <i class="material-icons-outlined align-middle me-2">info</i>{{ __('General Information') }}
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light-subtle">{{ __('Related Task') }}</th>
                            <td>
                                <a href="{{ route('admin_panel.tasks.profile', $expense->task->uuid) }}" class="fw-bold">
                                    {{ $expense->task->title }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light-subtle">{{ __('Amount') }}</th>
                            <td class="text-danger fw-bold fs-5">
                                TZS {{ number_2_format($expense->amount) }}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light-subtle">{{ __('Recorded By') }}</th>
                            <td>{{ $expense->user->name }} <br> <small class="text-muted">{{ $expense->created_at->format('d M, Y H:i') }}</small></td>
                        </tr>
                        <tr>
                            <th class="bg-light-subtle">{{ __('Approval Status') }}</th>
                            <td>{!! getExpenseBadge($expense->approved_at) !!}</td>
                        </tr>
                        @if($expense->approved_at)
                            <tr>
                                <th class="bg-light-subtle">{{ __('Approval Date') }}</th>
                                <td> {{ short_date_format_with_day($expense->approved_at) }} </td>
                            </tr>
                        @endif
                        <tr>
                            <th class="bg-light-subtle">{{ __('Description') }}</th>
                            <td>{!! $expense->description !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold">
            <i class="material-icons-outlined align-middle me-2">attach_file</i>{{ __('Attached Documents') }}
        </h6>
        <span class="badge bg-primary rounded-pill">{{ $expense->attachments->count() }} Files</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('File Name') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Size') }}</th>
                        <th>{{ __('Uploaded By') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th class="text-end">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expense->attachments as $doc)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="fm-icon-box radius-10 bg-light-primary text-primary me-2">
                                        <i class="material-icons-outlined fs-6">
                                            {{ in_array(strtolower($doc->extension), ['jpg','png','jpeg']) ? 'image' : 'description' }}
                                        </i>
                                    </div>
                                    <span class="text-truncate" style="max-width: 200px;" title="{{ $doc->original_name }}">
                                        {{ $doc->original_name ?? $doc->name }}
                                    </span>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ strtoupper($doc->extension) }}</span></td>
                            <td><small class="text-muted">{{ formatBytes($doc->size) }}</small></td>
                            <td>{{ optional($doc->uploadedBy)->name }}</td>
                            <td><small>{{ short_date_format_with_day($doc->created_at) }}</small></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2 px-2">
                                    <a href="{{ route('admin_panel.attachments.download', $doc->uuid) }}"
                                       class="btn btn-sm btn-outline-primary radius-30" title="Download">
                                        <i class="material-icons-outlined fs-6">download</i>
                                    </a>

                                    @if(in_array(strtolower($doc->extension), ['jpg','png','jpeg']))
                                        <button type="button" class="btn btn-sm btn-outline-info radius-30 receipt-img-link" data-url="{{ route('admin_panel.attachments.view_file', $doc->uuid) }}"
                                                onclick="previewImage('{{ route('admin_panel.attachments.view_file', $doc->uuid) }}')">
                                            <i class="material-icons-outlined fs-6">visibility</i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="material-icons-outlined fs-2">cloud_off</i>
                                <p class="mb-0">{{ __('No supporting documents found.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
