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
        $(document).ready(function() {
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
                order: [[3, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                }
            });
        });
    </script>
@endpush
