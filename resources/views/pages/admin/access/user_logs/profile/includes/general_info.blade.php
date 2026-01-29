<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-light text-muted">
                <strong>{{ __('label.general_info') }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                    <tr>
                        <th>@lang('label.user')</th>
                        <td>{{ optional($userLog->user)->name ?? $userLog->username }}</td>
                    </tr>
                    <tr>
                        <th>@lang('label.log_type')</th>
                        <td>{{ optional($userLog->logType)->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('label.browser')</th>
                        <td>{{ $userLog->browser }} {{ $userLog->browser_version }}</td>
                    </tr>
                    <tr>
                        <th>@lang('label.device')</th>
                        <td>{{ $userLog->device }}</td>
                    </tr>
                    <tr>
                        <th>@lang('label.platform')</th>
                        <td>{{ $userLog->platform }} {{ $userLog->platform_version }}</td>
                    </tr>
                    <tr>
                        <th>@lang('label.is_desktop')</th>
                        <td>
                            @if($userLog->isdesktop)
                                <div class="badge badge-primary">
                                    {{ config('constants.options.yes') }}
                                </div>
                            @else
                                <div class="badge badge-danger">
                                    {{ config('constants.options.no') }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('label.is_phone')</th>
                        <td>
                            @if($userLog->isphone)
                                <div class="badge badge-primary">
                                    {{ config('constants.options.yes') }}
                                </div>
                            @else
                                <div class="badge badge-danger">
                                    {{ config('constants.options.no') }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('label.is_mobile')</th>
                        <td>
                            @if($userLog->is_mobile)
                                <div class="badge badge-primary">
                                    {{ config('constants.options.yes') }}
                                </div>
                            @else
                                <div class="badge badge-danger">
                                    {{ config('constants.options.no') }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('label.is_tablet')</th>
                        <td>
                            @if($userLog->istablet)
                                <div class="badge badge-primary">
                                    {{ config('constants.options.yes') }}
                                </div>
                            @else
                                <div class="badge badge-danger">
                                    {{ config('constants.options.no') }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('label.is_robot')</th>
                        <td>
                            @if($userLog->istablet)
                                <div>
                                    <span class="badge badge-primary"> {{ config('constants.options.yes') }} </span> {{ $userLog->robot_name }}
                                </div>
                            @else
                                <div class="badge badge-danger">
                                    {{ config('constants.options.no') }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('label.location')</th>
                        <td>{{ $userLog->location ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>@lang('label.created_at')</th>
                        <td>{{ short_date_format_with_day($userLog->created_at) }}, {{ time_date_format($userLog->created_at) }}</td>
                    </tr>
                    <tr>
                        <th>@lang('label.updated_at')</th>
                        <td>{{ short_date_format_with_day($userLog->updated_at) }}, {{ time_date_format($userLog->updated_at) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
