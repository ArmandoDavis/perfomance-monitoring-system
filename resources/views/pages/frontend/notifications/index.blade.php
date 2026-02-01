@extends('layouts.admin.app')

@section('title', __('Notifications Inbox'))

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ __('Account') }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Notifications Inbox') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="material-icons-outlined me-2">notifications</i>{{ __('Your Notifications') }}</h5>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.mark_all_read') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="material-icons-outlined fs-6">done_all</i> {{ __('Mark all as read') }}
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <div class="list-group-item list-group-item-action border-bottom p-3 {{ $notification->read_at ? 'bg-light-subtle' : 'border-start border-4 border-primary' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="notify rounded-circle {{ $notification->read_at ? 'bg-light text-muted' : 'bg-light-primary text-primary' }}">
                            <span class="material-icons-outlined fs-5">
                                {{ str_contains($notification->data['message'], 'revoked') ? 'person_remove' : (str_contains($notification->data['message'], 'modified') ? 'edit_note' : 'notifications') }}
                            </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold {{ $notification->read_at ? 'text-muted' : 'text-dark' }}">
                                        {{ $notification->data['title'] ?? __('Task Update') }}
                                    </h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-2 text-secondary small">
                                    {{ $notification->data['message'] }}
                                </p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-link p-0 text-decoration-none fw-bold">
                                        {{ __('View Detail') }}
                                    </a>
                                    @if(!$notification->read_at)
                                        <span class="badge bg-primary rounded-pill small">{{ __('New') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <img src="{{ asset('assets/images/no-notifications.svg') }}" alt="No notifications" style="width: 150px;" class="mb-3 opacity-50">
                        <h6 class="text-muted">{{ __('Your inbox is empty') }}</h6>
                    </div>
                @endforelse
            </div>
        </div>
        @if($notifications->hasPages())
            <div class="card-footer bg-white border-top">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection
