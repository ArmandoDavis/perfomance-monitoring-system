@extends('layouts.admin.app')
@section('title', 'Permissions')
@include('includes.assets.datatable_assets')

@section('content')
    <div class="container-fluid">
        <div id="content">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle" id="permission-table" width="100%">
                            <thead class="table-light">
                            <tr>
                                <th width="50">Sn</th>
                                <th>Name</th>
                                <th>Guard Name</th>
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
        $(document).ready(function() {
            $('#permission-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin_panel.permissions.get_all_for_dt') }}',
                    type: 'GET'
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'permissions.name', orderable: true, searchable: true },
                    { data: 'guard_name', name: 'permissions.guard_name', orderable: true, searchable: true },
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search permissions..."
                }
            });
        });
    </script>
@endpush
