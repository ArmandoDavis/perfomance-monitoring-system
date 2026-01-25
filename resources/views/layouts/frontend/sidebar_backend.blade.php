
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
        <!--navigation-->
        <ul class="metismenu" id="sidenav">
            <li>
                <a href="{{ route('admin_panel.dashboard') }}">
                    <div class="parent-icon">
                        <i class="material-icons-outlined">house</i>
                    </div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <li>
                <a href="{{ route('frontend.tasks.index') }}">
                    <div class="parent-icon">
                        <i class="material-icons-outlined">task</i>
                    </div>
                    <div class="menu-title">My Tasks</div>
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
            <a class="footer-icon  dropdown-toggle dropdown-toggle-nocaret option" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-icons-outlined">
              info
            </span>
            </a>
            <div class="dropdown-menu dropdown-option dropdown-menu-end shadow">
                <div>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);"><i class="material-icons-outlined fs-6">inventory_2</i>Archive All</a>
                </div>
                <div>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);">
                        <i class="material-icons-outlined fs-6">done_all</i>Mark all as read
                    </a>
                </div>
                <div>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);">
                        <i class="material-icons-outlined fs-6">mic_off</i>Disable Notifications
                    </a>
                </div>
                <div>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);">
                        <i class="material-icons-outlined fs-6">grade</i>What's new ?</a>
                </div>
                <div>
                    <hr class="dropdown-divider">
                </div>
                <div>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:void(0);">
                        <i class="material-icons-outlined fs-6">leaderboard</i>Reports
                    </a>
                </div>
            </div>
        </div>

    </div>
</aside>
<!--end sidebar-->
