<!--start header-->
<header class="top-header">
    <nav class="navbar navbar-expand align-items-center gap-4">
        <div class="btn-toggle">
            <a href="javascript:void(0)"><i class="material-icons-outlined">menu</i></a>
        </div>
        <div class="search-bar flex-grow-1">
            <div class="position-relative">
                <input id="global-search-input" class="form-control rounded-5 px-5 search-control d-lg-block d-none" type="text" placeholder="Search tasks, users, departments...">
                <span class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
                <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span>
                <div class="search-popup p-3">
                    <div class="card rounded-4 overflow-hidden">
                        <div class="card-header d-lg-none">
                            <div class="position-relative">
                                <input class="form-control rounded-5 px-5 mobile-search-control" type="text" placeholder="Search">
                                <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                                <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 mobile-search-close">close</span>
                            </div>
                        </div>

                        <div class="card-body search-content">
                            <p class="search-title">Search Results</p>
                            <div id="global-search-results" class="search-list d-flex flex-column gap-2">
                                <div class="text-muted small">Type to search tasks, users or departments...</div>
                            </div>
                        </div>

                        <div class="card-footer text-center bg-transparent">
                            <a href="javascript:void(0);" class="btn w-100">See All Search Results</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ul class="navbar-nav gap-1 nav-right-links align-items-center">
            <li class="nav-item d-lg-none mobile-search-btn">
                <a class="nav-link" href="javascript:void(0)"><i class="material-icons-outlined">search</i></a>
            </li>

            {{-- notification center --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" data-bs-auto-close="outside" data-bs-toggle="dropdown" href="javascript:void(0)">
                    <i class="material-icons-outlined">notifications</i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge-notify">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
                    <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="notiy-title mb-0">{{ __('Notifications') }}</h5>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle dropdown-toggle-nocaret option" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-icons-outlined">more_vert</span>
                            </button>
                            <div class="dropdown-menu dropdown-option dropdown-menu-end shadow">
                                {{-- Senior Move: Route to mark all as read --}}
                                <div>
                                    <form action="{{ route('notifications.mark_all_read') }}" method="POST" id="mark-all-read-form">
                                        @csrf
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;" onclick="document.getElementById('mark-all-read-form').submit();">
                                            <i class="material-icons-outlined fs-6">done_all</i>{{ __('Mark all as read') }}
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="notify-list">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            @php
                                $icon = 'notifications';
                                $bgClass = 'bg-light-primary text-primary';

                                if(str_contains($notification->data['message'], 'revoked')) {
                                    $icon = 'person_remove';
                                    $bgClass = 'bg-light-danger text-danger';
                                } elseif(str_contains($notification->data['message'], 'updated')) {
                                    $icon = 'edit_note';
                                    $bgClass = 'bg-light-warning text-warning';
                                }
                            @endphp

                            <a class="dropdown-item border-bottom py-2" href="{{ $notification->data['action_url'] ?? '#' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="notify {{ $bgClass }} rounded-circle">
                                        <span class="material-icons-outlined fs-6">{{ $icon }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="msg-name mb-0 small fw-bold">
                                            {{ $notification->data['title'] ?? __('Task Update') }}
                                            <span class="msg-time float-end text-muted small">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                        </h6>
                                        <p class="msg-info mb-0 small text-truncate" style="max-width: 220px;">
                                            {{ $notification->data['message'] }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4">
                                <i class="material-icons-outlined fs-1 text-muted">notifications_none</i>
                                <p class="mb-0 text-muted small">{{ __('No new notifications') }}</p>
                            </div>
                        @endforelse
                    </div>

                    @if(auth()->user()->notifications->count() > 0)
                        <div class="text-center py-2 border-top">
                            <a href="{{ route('notifications.index') }}" class="text-primary small fw-bold">{{ __('View All Notifications') }}</a>
                        </div>
                    @endif
                </div>
            </li>

            {{-- customize --}}
            <li class="nav-item">
                <a class="nav-link position-relative" data-bs-toggle="offcanvas" href="#staticBackdrop">
                    <i class="material-icons-outlined">tune</i>
                </a>
            </li>

            {{-- dropdwon menus --}}
            <li class="nav-item dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/images/avatars/user-dummy.jpg')}}" class="rounded-circle p-1 border" width="45" height="45">
                </a>
                <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
                    <a class="dropdown-item  gap-2 py-2" href="javascript:void(0)">
                        <div class="text-center">
                            <img src="{{ asset('assets/images/avatars/user-dummy.jpg')}}" class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="">
                            <h5 class="user-name mb-0 fw-bold">{{ userFullName() }}</h5>
                        </div>
                    </a>
                    <hr class="dropdown-divider">
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin_panel.user_profile.my_profile') }}">
                        <i class="material-icons-outlined">person_outline</i>Profile
                    </a>

                    <hr class="dropdown-divider">
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="material-icons-outlined">power_settings_new</i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>
</header>
<!--end top header-->
