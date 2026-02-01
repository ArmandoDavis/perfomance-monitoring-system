
<!--start sidebar-->
<aside class="sidebar-wrapper">
    <div class="sidebar-header">
        <x-application-logo />
        <div class="logo-name flex-grow-1 d-none">
            <h5 class="mb-0">{{ config('app.name') }}</h5>
        </div>
        <div class="sidebar-close">
            <span class="material-icons-outlined">close</span>
        </div>
    </div>

    <div class="sidebar-nav" data-simplebar="true">
        <ul class="metismenu" id="sidenav">
            <li>
                <a href="{{ route('frontend.dashboard') }}">
                    <div class="parent-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="menu-title">{{ __('Dashboard') }}</div>
                </a>
            </li>

            @can('task.view')
                <li class="menu-label">{{ __('My Work') }}</li>

                {{-- All My Tasks --}}
                <li>
                    <a href="{{ route('frontend.tasks.index') }}">
                        <div class="parent-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="menu-title">{{ __('All My Tasks') }}</div>
                    </a>
                </li>

                {{-- Shared with Me --}}
                <li>
                    <a href="{{ route('frontend.tasks.shared') }}">
                        <div class="parent-icon">
                            <i class="fas fa-user-group"></i>
                        </div>
                        <div class="menu-title">{{ __('Shared with Me') }}</div>
                    </a>
                </li>

                {{-- Transferred to Me --}}
                <li>
                    <a href="{{ route('frontend.tasks.transferred') }}">
                        <div class="parent-icon">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <div class="menu-title">{{ __('Transferred to Me') }}</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('frontend.tasks.next_actions') }}">
                        <div class="parent-icon">
                            <i class="fas fa-clock-rotate-left"></i>
                        </div>
                        <div class="menu-title">{{ __('Next Actions') }}</div>
                    </a>
                </li>
            @endcan

            {{-- Support Section --}}
            <li class="menu-label">{{ __('Support') }}</li>
            <li>
                <a href="javascript:void(0)">
                    <div class="parent-icon">
                        <i class="fas fa-circle-question"></i>
                    </div>
                    <div class="menu-title">{{ __('Help Center') }}</div>
                </a>
            </li>
        </ul>
    </div>


    <div class="sidebar-bottom gap-4">
        <div class="dark-mode">
            <a href="javascript:void(0);" class="footer-icon dark-mode-icon">
                <i class="material-icons-outlined">dark_mode</i>
            </a>
        </div>
        <div class="dropdown dropup-center dropup dropdown-laungauge">
            <a class="dropdown-toggle dropdown-toggle-nocaret footer-icon" href="javascript:void(0)" data-bs-toggle="dropdown">
                <img src="{{ asset('assets/images/county/us.png')}}" width="22" alt="">
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item d-flex align-items-center py-2" href="javascript:void(0)"><img src="{{ asset('assets/images/county/us.png')}}" width="20" alt=""><span class="ms-2">English</span></a>
                </li>
            </ul>
        </div>
        <div class="dropdown dropup-center dropup dropdown-help">
            <a class="footer-icon dropdown-toggle dropdown-toggle-nocaret option" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('System Help & Actions') }}">
                <span class="material-icons-outlined">info</span>
            </a>
            <div class="dropdown-menu dropdown-option dropdown-menu-end shadow border-0">
                {{-- Header ya Dropup --}}
                <div class="dropdown-header border-bottom mb-1">
                    <h6 class="mb-0 text-dark fw-bold small">{{ __('System Help') }}</h6>
                </div>

                {{-- Quick Notification Action --}}
                <div>
                    <form action="{{ route('notifications.mark_all_read') }}" method="POST" id="footer-mark-read">
                        @csrf
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);" onclick="document.getElementById('footer-mark-read').submit();">
                            <i class="material-icons-outlined fs-6 text-success">done_all</i>{{ __('Clear Notifications') }}
                        </a>
                    </form>
                </div>

                {{-- Help Links --}}
                <div>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('frontend.notifications.index') }}">
                        <i class="material-icons-outlined fs-6 text-primary">history</i>{{ __('Whats new') }}
                    </a>
                </div>

                <div class="dropdown-divider"></div>

                {{-- Support / Documentation --}}
                <div>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#" target="_blank">
                        <i class="material-icons-outlined fs-6 text-info">menu_book</i>{{ __('User Manual') }}
                    </a>
                </div>

                <div>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="mailto:info@teganas.co.tz">
                        <i class="material-icons-outlined fs-6 text-danger">contact_support</i>{{ __('Get Support') }}
                    </a>
                </div>

                {{-- System Version (Senior Move) --}}
                <div class="dropdown-header border-top mt-1 pt-2">
                    <small class="text-muted">{{ __('Version') }} 2.1.0-stable</small>
                </div>
            </div>
        </div>

    </div>
</aside>
<!--end sidebar-->
