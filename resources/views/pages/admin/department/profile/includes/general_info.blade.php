<div class="row">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header text-muted">
                <strong>{{ __('General Info') }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{ $department->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Abbreviation')</th>
                            <td>{{ $department->abbreviation }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Status')</th>
                            <td>{!! getStatusBadge($department->is_active) !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('Date Created')</th>
                            <td>{{ short_date_format_with_day($department->updated_at) }}, {{ time_date_format($department->created_at) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Date updated')</th>
                            <td>{{ short_date_format_with_day($department->updated_at) }}, {{ time_date_format($department->updated_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
