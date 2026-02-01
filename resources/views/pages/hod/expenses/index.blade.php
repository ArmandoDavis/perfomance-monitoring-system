@extends('layouts.hod.app')
@include('includes.assets.datatable_assets')

@section('content')
    <div class="card radius-10">
        <div class="card-header d-flex align-items-center justify-content-between bg-transparent">
            <h6 class="mb-0 fw-bold"><i class="material-icons-outlined align-middle me-2">payments</i>{{ __('Expense Management') }}</h6>
            <a href="{{ route('hod_panel.expenses.create') }}" class="btn btn-primary btn-sm radius-30 px-3">
                <i class="material-icons-outlined">add</i> {{ __('Record Expense') }}
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="expenses_table" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Task Name') }}</th>
                            <th>{{ __('Amount (TZS)') }}</th>
                            <th>{{ __('Recorded By') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const url = "{{ url('/') }}";

            $('#expenses_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('hod_panel.expenses.get_all_for_dt') }}",
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'task_name', name: 'task.title' },
                    { data: 'amount', name: 'amount' },
                    { data: 'user_name', name: 'user.name' },
                    { data: 'status', name: 'approved_at', orderable: false, searchable: false },
                ],
                order: [[0, 'desc']],
                columnDefs: [
                    { className: "text-end", "targets": [2] }
                ],
                fnRowCallback: function (nRow, aData) {
                    $(nRow).css('cursor', 'pointer');
                    $(nRow).on('click', function (e) {
                        if (!$(e.target).closest('button, a').length) {
                            window.location.href = url + "/hod_panel/expenses/profile/" + aData['uuid'];
                        }
                    });
                }
            });
        });
    </script>
@endpush
