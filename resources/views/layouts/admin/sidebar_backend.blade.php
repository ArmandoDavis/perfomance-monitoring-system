
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
                <a href="{{ route('home') }}">
                    <div class="parent-icon">
                        <i class="fas fa-house"></i>
                    </div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            @can('task.view')
                <li class="menu-label">Task</li>
                <li>
                    <a href="javascript:void(0)" class="has-arrow">
                        <div class="parent-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="menu-title">Task Management</div>
                    </a>
                    <ul>
                        @can('task.manage')
                            <li>
                                <a href="{{ route('admin_panel.tasks.index') }}">
                                    <i class="fas fa-list-check"></i> All Tasks
                                </a>
                            </li>
                        @endcan

                        <li>
                            <a href="{{ route('admin_panel.tasks.my_tasks') }}">
                                <i class="fas fa-user-gear"></i> My Tasks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin_panel.tasks.shared') }}">
                                <i class="fas fa-users-viewfinder"></i> Shared Tasks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin_panel.tasks.next_actions') }}">
                                <i class="fas fa-calendar-check"></i> Next Actions
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin_panel.tasks.transferred') }}">
                                <i class="fas fa-share-from-square"></i> Transferred Tasks
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan

            @canany(['user.view', 'expense.view'])
                <li class="menu-label">Hr</li>
                <li>
                    <a href="javascript:void(0)" class="has-arrow">
                        <div class="parent-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div class="menu-title">Hr</div>
                    </a>
                    <ul>
                        @can('expense.view')
                            <li class="{{ request()->routeIs('admin_panel.expenses.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('admin_panel.expenses.index') }}">
                                    <div class="parent-icon">
                                        <i class="material-icons-outlined">payments</i>
                                    </div>
                                    <div class="menu-title">{{ __('Expenses') }}</div>
                                </a>
                            </li>
                        @endcan

                        @can('user.view')
                            <li>
                                <a href="{{ route('admin_panel.users.index') }}">
                                    <i class="fas fa-user-group"></i> Users
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @if(auth()->user()->hasRole('Admin'))
                <li class="menu-label">Administration</li>
                <li>
                    <a href="javascript:void(0)" class="has-arrow">
                        <div class="parent-icon">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div class="menu-title">Administration</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin_panel.role.index') }}">
                                <i class="fas fa-user-shield"></i> Roles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin_panel.permissions.index') }}">
                                <i class="fas fa-key"></i> Permissions
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('audits.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin_panel.audits.index') }}">
                                <div class="parent-icon"><i class="material-icons-outlined">history_toggle_off</i></div>
                                <div class="menu-title">{{ __('System Audits') }}</div>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin_panel.departments.index') }}">
                                <i class="fas fa-sitemap"></i> Departments
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
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
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('notifications.index') }}">
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
