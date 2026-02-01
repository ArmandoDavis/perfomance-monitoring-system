@extends('layouts.hod.app')
@section('title', __('Task List'))
@include('includes.assets.datatable_assets')
@include('includes.assets.sweetalert_assets')
@include('includes.assets.confirm_alert_assets')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-end mb-4">
        @can('task.create')
            <a href="{{ route('hod_panel.tasks.create') }}" class="d-none d-sm-inline-block btn btn-xl btn-primary shadow-sm">
                <i class="bi bi-plus-circle text-white-50"></i>  Add Task
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="taskTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{__('Title')}}</th>
                            <th>{{__('Department')}}</th>
                            <th>{{__('Created By')}}</th>
                            <th>{{__('Budget allocated')}}</th>
                            <th>{{__('Amount spent')}}</th>

                            <th>{{__('Remaining budget')}}</th>
                            <th>{{__('Is Active?')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Start date')}}</th>
                            <th>{{__('End date')}}</th>
                            <th>{{__('Completed date')}}</th>
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

        $(document).ready(function() {
            $('#taskTable').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [ 'copy', 'excel', 'pdf', 'print'],
                ajax: {
                    url: "{{ route('hod_panel.tasks.get_all_for_dt') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_type = "{{ $filter_type ?? 'all' }}";
                    }
                },
                columns: [
                    { data: 'title', name: 'title', orderable: true, searchable: true },
                    { data: 'department_name', name: 'department_name', orderable: true, searchable: true },
                    { data: 'created_by', name: 'created_by', orderable: true, searchable: true },
                    { data: 'allocated_budget', name: 'allocated_budget', orderable: true, searchable: true },
                    { data: 'spent_amount', name: 'spent_amount', orderable: true, searchable: true },
                    { data: 'remaining_budget', name: 'remaining_budget', orderable: true, searchable: true },
                    { data: 'is_active_badge', name: 'is_active_badge', orderable: true, searchable: true },
                    { data: 'status_badge', name: 'status_badge', orderable: true, searchable: true },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date', orderable: false },
                    { data: 'completed_at', name: 'completed_at', orderable: false },
                ],
                order: [[3, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                },
                "fnRowCallback": function(nRow, aData) {
                    $(nRow).off('click').on('click', function(e) {
                        if (!$(e.target).closest('button, a, input').length) {
                            document.location.href = url + "/hod_panel/tasks/profile/" + aData['uuid'];
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
