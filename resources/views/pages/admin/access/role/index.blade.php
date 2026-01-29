@extends('layouts.admin.app')
@section('title', __('label.roles'))
@include('includes.assets.datatable_assets')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-end mb-4">
        <a href="{{ route('admin_panel.role.create') }}" class="d-none d-sm-inline-block btn btn-xl btn-primary shadow-sm">
            <i class="bi bi-plus-circle text-white-50"></i>  Add role
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="roleTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>@lang('Sn')</th>
                        <th>{{ __('Name') }}</th>
                        <th scope="col" class="px-6 py-3">{{ __('Assigned users') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const url = "{{ url('/') }}";
        $('#roleTable').DataTable({
            ajax: {
                url: '{{ route('admin_panel.role.get_all_for_dt') }}',
                type: 'GET'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'roles.name', orderable: true, searchable: true },
                {
                    data: 'users_count',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        let count = data || 0;
                        return `
                            <div class="position-relative">
                                <span class="badge bg-primary text-white px-2 py-1 rounded-pill users-count-badge"
                                      data-role-uuid="${row.uuid}"
                                      data-users-count="${count}">
                                    ${count} user${count !== 1 ? 's' : ''} <i class="fa fa-eye"></i>
                                </span>
                                <div class="users-preview-popup position-absolute bg-white p-3 shadow rounded d-none">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                },
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).click(function(e) {
                    // Don't navigate if clicking on the users count badge
                    if (!$(e.target).closest('.users-count-badge').length) {
                        document.location.href = url + "/admin_panel/role/profile/" + aData['uuid'];
                    }
                }).hover(function() {
                    $(this).css('cursor','pointer');
                }, function() {
                    $(this).css('cursor','auto');
                });
            }
        });

        // Add hover effect for users count
        $(document).on('mouseenter', '.users-count-badge', function() {
            const $this = $(this);
            const roleUuid = $this.data('role-uuid');
            const $popup = $this.siblings('.users-preview-popup');

            // Show loading spinner
            $popup.removeClass('d-none').css({
                'z-index': 1000,
                'min-width': '200px',
                'top': '100%',
                'left': '50%',
                'transform': 'translateX(-50%)'
            });

            // Fetch users data
            $.ajax({
                url: `{{ route('admin_panel.role.users_preview', ['role' => '__ROLE__']) }}`.replace('__ROLE__', roleUuid),
                type: 'GET',
                success: function(response) {
                    $popup.html(response.html);
                },
                error: function() {
                    $popup.html('<p class="text-danger">Failed to load users</p>');
                }
            });
        });

        // Hide popup when mouse leaves
        $(document).on('mouseleave', '.users-count-badge', function() {
            $(this).siblings('.users-preview-popup').addClass('d-none');
        });

        // Handle click to navigate to role users page
        $(document).on('click', '.users-count-badge', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const roleUuid = $(this).data('role-uuid');
            window.location.href = `{{ route('admin_panel.role.role_user', ['role' => '__ROLE__']) }}`.replace('__ROLE__', roleUuid);
        });
    </script>
@endpush
