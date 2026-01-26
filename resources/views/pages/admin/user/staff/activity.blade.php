@extends('layouts.backend.app')
@section('title', __('label.user_caused_activity'))
@include('includes.assets.datatable_assets')

@section('content')
    <div class="card shadow-sm border-0 rounded">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Activity Log for {{ $user->name }}</h4>
                <a href="{{ route('backend.users.staff.profile', $user->uid) }}" class="btn btn-sm btn-white">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Profile
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="caused_activity-table">
                    <thead>
                        <tr>
                            <th width="30%">{{__('label.description')}}</th>
                            <th width="25%">{{__('label.subject')}}</th>
                            <th width="25%">{{__('label.changes')}}</th>
                            <th width="20%">{{__('label.date')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for viewing changes -->
    <div class="modal fade" id="changesModal" tabindex="-1" aria-labelledby="changesModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changesModalLabel">{{ trans('labels.backend.activity.details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ trans('buttons.general.close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6>{{ trans('labels.backend.activity.old_values') }}</h6>
                            <pre id="old-values" class="bg-light p-3 rounded border"></pre>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ trans('labels.backend.activity.new_values') }}</h6>
                            <pre id="new-values" class="bg-light p-3 rounded border"></pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('buttons.general.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const userUid = '{{ $user->uid }}';

            const table = $('#caused_activity-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: `{{ route('backend.users.staff.get_caused_activity_for_dt', ['user' => '__USER__']) }}`.replace('__USER__', userUid),
                    type: 'GET'
                },
                columns: [
                    {
                        data: 'description',
                        name: 'description',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'subject',
                        name: 'subject',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'changes',
                        name: 'changes',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'created_at',
                        orderable: true,
                        searchable: false
                    }
                ],
                order: [[3, 'desc']], // Order by date (4th column) descending
                dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3],
                            format: {
                                body: function (data, row, column, node) {
                                    // Strip HTML tags for export
                                    return $(data).text() || data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3],
                            format: {
                                body: function (data, row, column, node) {
                                    // Strip HTML tags for print
                                    return $(data).text() || data;
                                }
                            }
                        }
                    }
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
                    lengthMenu: "{{ trans('pagination.results') }} : _MENU_"
                },
                lengthMenu: [10, 20, 50, 100],
                pageLength: 10,
            });

            // Handle view changes button click (using event delegation)
            table.on('click', '.view-changes', function() {
                try {
                    const propertiesStr = $(this).attr('data-properties');
                    if (!propertiesStr) {
                        throw new Error('No properties data found');
                    }

                    const properties = JSON.parse(propertiesStr);
                    const oldValues = properties.old || {};
                    const newValues = properties.attributes || {};

                    $('#old-values').html(JSON.stringify(oldValues, null, 2));
                    $('#new-values').html(JSON.stringify(newValues, null, 2));

                    $('#changesModal').modal('show');
                } catch (error) {
                    const toastOptions = {
                        timeOut: 3000,
                        positionClass: 'toast-bottom-right',
                        closeButton: true
                    };
                    toastr.error('Failed to load changes data', '', toastOptions);
                }
            });
        });
    </script>
@endpush
