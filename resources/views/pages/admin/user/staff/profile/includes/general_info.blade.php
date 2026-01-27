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
                            <th>@lang('Full name')</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Email')</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Phone')</th>
                            <td>{{ $user->phone }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Username')</th>
                            <td>{{ $user->email ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('User type')</th>
                            <td>{{ $user->userType->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Status')</th>
                            <td>{!! getStatusBadge($user->is_active) !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Summary -->
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header text-muted">
                <strong>{{ __('Sidebar summary') }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th>@lang('Date Created')</th>
                            <td>{{ short_date_format_with_day($user->updated_at) }}, {{ time_date_format($user->created_at) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Date updated')</th>
                            <td>{{ short_date_format_with_day($user->updated_at) }}, {{ time_date_format($user->updated_at) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Is password updated?')</th>
                            <td>{!! getBooleanBadge($user->is_password_updated) !!} </td>
                        </tr>
                        <tr>
                            <th>@lang('Date email verified')</th>
                            <td>
                                @if($user->email_verified_at === null)
                                    <span class="text-danger">{{ __('Email not verified') }}</span>
                                @else
                                    {{ short_date_format_with_day($user->email_verified_at) }}, {{ time_date_format($user->email_verified_at) }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Roles -->
        <div class="card">
            <div class="card-header text-muted">
                <strong>Roles</strong>
            </div>
            <div class="card-body">
                @if($user->roles->isNotEmpty())
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary me-1">
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </span>
                    @endforeach
                @else
                    <span class="text-muted">No roles assigned</span>
                @endif
            </div>
        </div>
    </div>
</div>
