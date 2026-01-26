@extends('layouts.admin.app')
@section('title', __('Add staff'))
@include('includes.assets.datatable_assets')

@section('breadcrumb-action')
    <a href="{{ route('admin_panel.users.create') }}" class="btn btn-primary d-flex align-items-center">
        <i class="ti ti-circle-plus me-2"></i> {{__('Add staff')}}
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="custom-datatable-filter table-responsive">
                        <table class="table" id="staff_user_table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{__('Full name')}}</th>
                                    <th>{{__('Email')}}</th>
                                    <th>{{__('Phone')}}</th>
                                    <th>{{__('Is admin')}}</th>
                                    <th>{{__('status')}}</th>
                                    <th>{{__('Date registed')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var url = "{{ url('/') }}";

        $(document).ready(function() {
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
                    { data: 'admin_badge', name: 'is_super_admin', orderable: false, searchable: false },
                    { data: 'status_badge', name: 'is_active', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                ],
                language: {
                    dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                        "<'table-responsive'tr>" +
                        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",

                    paginate: {
                        previous: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },

                    info: "{{ trans('pagination.showing_page') }}",
                    search: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    searchPlaceholder: "{{ trans('label.search') }}",
                    lengthMenu: "{{ trans('Result') }} : _MENU_"
                },
                lengthMenu: [10, 20, 50, 100],
                pageLength: 10,
                "fnRowCallback": function(nRow, aData) {
                    $(nRow).off('click').on('click', function(e) {
                        if (!$(e.target).closest('button, a, input').length) {
                            document.location.href = url + "/admin_panel/users/profile/" + aData['uid'];
                        }
                    }).hover(
                        function() { $(this).css('cursor', 'pointer'); },
                        function() { $(this).css('cursor', 'auto'); }
                    );
                }
            });
        });
    </script>
@endpush
