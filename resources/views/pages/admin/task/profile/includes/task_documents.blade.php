<div class="card-body">
    @if($task->documents->count())
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Size') }}</th>
                    <th>{{ __('Uploaded By') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($task->documents as $doc)
                    <tr>
                        <td>{{ $doc->original_name ?? $doc->name }}</td>
                        <td>{{ strtoupper($doc->extension) }}</td>
                        <td>{{ number_format($doc->size / 1024, 2) }} KB</td>
                        <td>{{ optional($doc->uploadedBy)->name }}</td>
                        <td>{{ short_date_format_with_day($doc->created_at) }}</td>
                        <td class="text-end">
                            <a href="{{ route('attachments.download', $doc->uuid) }}" class="btn btn-sm btn-outline-success">
                                <i class="material-icons-outlined">download</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted mb-0">{{ __('No documents uploaded yet.') }}</p>
    @endif
</div>
