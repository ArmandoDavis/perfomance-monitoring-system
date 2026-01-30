@extends('layouts.admin.app')
@section('title', __('Staff List'))
@include('includes.assets.datatable_assets')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-end mb-4">
        @canany(['user.create'])
            <a href="{{ route('admin_panel.users.create') }}" class="d-none d-sm-inline-block btn btn-xl btn-primary shadow-sm">
                <i class="bi bi-plus-circle text-white-50"></i>  Add Staff
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="staff_user_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Full name') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('User type') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Date registered') }}</th>
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

        $(document).ready(function () {
            $('#staff_user_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin_panel.users.get_staff_user_for_dt') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'name', name: 'name', orderable: true, searchable: true },
                    { data: 'phone', name: 'phone', orderable: true, searchable: true },
                    { data: 'email', name: 'email', orderable: true, searchable: true },
                    { data: 'user_type', name: 'user_type', orderable: true, searchable: true },
                    { data: 'status_badge', name: 'is_active', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                ],
                language: {
                    dom:
                        "<'dt--top-section row mb-2'<'col-12 col-md-6 d-flex justify-content-center justify-content-md-start'l>" +
                        "<'col-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-2 mt-md-0'f>>" +
                        "<'table-responsive'tr>" +
                        "<'dt--bottom-section row mt-2'<'col-12 col-md-6 d-flex justify-content-center justify-content-md-start'i>" +
                        "<'col-12 col-md-6 d-flex justify-content-center justify-content-md-end'p>>",

                    paginate: {
                        previous: '&laquo;',
                        next: '&raquo;'
                    },

                    info: "{{ trans('Showing pages') }}",
                    searchPlaceholder: "{{ trans('Search') }}",
                    lengthMenu: "{{ trans('Result') }} : _MENU_"
                },
                lengthMenu: [10, 20, 50, 100],
                pageLength: 10,

                fnRowCallback: function (nRow, aData) {
                    $(nRow).off('click').on('click', function (e) {
                        if (!$(e.target).closest('button, a, input').length) {
                            window.location.href = url + "/admin_panel/users/profile/" + aData['uuid'];
                        }
                    }).hover(
                        function () { $(this).css('cursor', 'pointer'); },
                        function () { $(this).css('cursor', 'auto'); }
                    );
                }
            });
        });
    </script>
@endpush
