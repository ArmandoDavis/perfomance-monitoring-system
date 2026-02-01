@extends('layouts.frontend.app')
@section('title', __('My Activity Logs'))
@include('includes.assets.datatable_assets')

@section('content')
    <div class="card radius-10">
        <div class="card-header bg-transparent py-3">
            <h6 class="mb-0 fw-bold"><i class="material-icons-outlined align-middle me-2">person_search</i>{{ __('My Activity Logs') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="my-audits-table" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Action Taken') }}</th>
                            <th>{{ __('Module/Resource') }}</th>
                            <th>{{ __('IP Address') }}</th>
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
            $('#my-audits-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('frontend.audits.get_all_for_dt') }}",
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'event', name: 'event' },
                    { data: 'auditable_type', name: 'auditable_type' },
                    { data: 'ip_address', name: 'ip_address' },
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
@endpush
