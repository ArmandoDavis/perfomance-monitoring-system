@extends('layouts.admin.app')
@section('title', __('Department'))
@include('includes.assets.datatable_assets')
@include('includes.assets.sweetalert_assets')
@include('includes.assets.confirm_alert_assets')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-end mb-4">
        @can('department.manage')
            <a href="{{ route('admin_panel.departments.create') }}" class="d-none d-sm-inline-block btn btn-xl btn-primary shadow-sm">
                <i class="bi bi-plus-circle text-white-50"></i>  Add Department
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="departmentTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Abbreviation')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Created at')}}</th>
                            <th>{{__('Actions')}}</th>
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
            $('#departmentTable').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [ 'copy', 'excel', 'pdf', 'print'],
                ajax: {
                    url: "{{ route('admin_panel.departments.get_all_for_dt') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'name', name: 'name', orderable: true, searchable: true },
                    { data: 'abbreviation', name: 'abbreviation', orderable: true, searchable: true },
                    { data: 'status_badge', name: 'is_active', orderable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
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
                            window.location.href = url + "/admin_panel/departments/profile/" + aData['uuid'];
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
