@extends('layouts.admin.app')
@section('title', __('System Audit Logs'))
@include('includes.assets.datatable_assets')

@section('content')
    <div class="card radius-10">
        <div class="card-header bg-transparent py-3">
            <h6 class="mb-0 fw-bold"><i class="material-icons-outlined align-middle me-2">history</i>{{ __('System Audit Logs') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="audits-table" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Event') }}</th>
                            <th>{{ __('Resource') }}</th>
                            <th>{{ __('IP Address') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('pages.admin.audits.partials._details_modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#audits-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin_panel.audits.get_all_for_dt') }}",
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'user', name: 'user.name' },
                    { data: 'event', name: 'event' },
                    { data: 'auditable_type', name: 'auditable_type' },
                    { data: 'ip_address', name: 'ip_address' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']],
                language: {
                    searchPlaceholder: "{{ __('Search audits...') }}"
                }
            });
        });
    </script>
@endpush
