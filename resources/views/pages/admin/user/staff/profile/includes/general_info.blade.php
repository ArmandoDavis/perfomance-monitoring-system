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
                <strong>@lang('Roles')</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <td>
                                @if($user->roles->isNotEmpty())
                                    @foreach($user->roles as $role)
                                        {{ $role->display_name }}@if (!$loop->last), @endif
                                    @endforeach
                                @else
                                    {{ __('No roles assigned') }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input user-status-toggle"
                                        id="userStatusSwitch"
                                        data-id="{{ $user->id }}"
                                        {{ $user->is_active == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="userStatusSwitch">
                                        {{ $user->is_active ? 'Enabled' : 'Disabled' }}
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
