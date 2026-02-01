@extends('layouts.hod.app')
@section('title', __('User Profile'))

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">{{ __('My Profile') }}</h4>
            <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="fas fa-edit"></i> {{ __('Edit Profile') }}
            </a>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-body text-center">

                        {{-- Avatar --}}
                        <img src="{{ auth()->user()->avatar_url ?? asset('/assets/images/avatars/user-dummy.jpg') }}" class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
                        <h5 class="fw-bold">{{ auth()->user()->name }}</h5>
                        <p class="text-muted mb-1">{{ auth()->user()->email }}</p>

                        <span class="badge bg-success">
                            {{ auth()->user()->is_active ? __('Active') : __('Inactive') }}
                        </span>
                        <hr>
                    </div>
                </div>
            </div>

            {{-- DETAILS SECTION --}}
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header fw-bold">
                        {{ __('Personal Information') }}
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">{{ __('Full Name') }}</label>
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">{{ __('Email Address') }}</label>
                                <div class="fw-bold">{{ auth()->user()->email }}</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted">{{ __('Phone Number') }}</label>
                                <div class="fw-bold">{{ auth()->user()->phone ?? __('N/A') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">{{ __('Department') }}</label>
                                <div class="fw-bold">{{ optional(auth()->user()->department)->name ?? __('N/A') }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-muted">{{ __('Role') }}</label>
                                <div class="fw-bold">
                                    {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted">{{ __('Joined On') }}</label>
                                <div class="fw-bold">{{ auth()->user()->created_at->format('d M Y') }}</div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- SECURITY SECTION --}}
                <div class="card shadow mb-4">
                    <div class="card-header fw-bold">
                        {{ __('Security') }}
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold mb-0">{{ __('Password') }}</h6>
                                <small class="text-muted">{{ __('Last updated') }}: {{ auth()->user()->password_updated_at ?? __('N/A') }}</small>
                            </div>
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                {{ __('Change Password') }}
                            </a>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">{{ __('Two-Factor Authentication') }}</h6>
                                <small class="text-muted">
                                    {{ auth()->user()->two_factor_enabled ? __('Enabled') : __('Disabled') }}
                                </small>
                            </div>
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-secondary">
                                {{ __('Manage') }}
                            </a>
                        </div>

                    </div>
                </div>

                {{-- RECENT ACTIVITY --}}
                <div class="card shadow">
                    <div class="card-header fw-bold">
                        {{ __('Recent Activity') }}
                    </div>
                    <div class="card-body">
                        @if(isset($recentActivities) && $recentActivities->count())
                            <ul class="list-group list-group-flush">
                                @foreach($recentActivities as $activity)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $activity['title'] }}</strong>
                                            <div class="text-muted small">{{ $activity['description'] }}</div>
                                        </div>
                                        <span class="text-muted small">
                                            {{ $activity['date']->diffForHumans() }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">{{ __('No recent activity found.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.admin.user_profile.partial.modal')
@endsection

@push('scripts')
    <script>
        // Preview avatar before upload
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('avatar-input');
            const preview = document.getElementById('avatar-preview');

            if (input && preview) {
                input.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        preview.src = URL.createObjectURL(file);
                    }
                });
            }
        });
    </script>
@endpush
