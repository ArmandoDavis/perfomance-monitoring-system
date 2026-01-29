@extends('layouts.admin.app')
@section('title', 'Add Role')
@section('content')
    <div class="container-fluid">
        <div id="content">
            <form action="{{ route('admin_panel.role.store') }}" method="POST" name="create" autocomplete="off" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="action_type" value="1">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow-sm">
                            <div class="card-body">

                                <div class="row mb-4">
                                    <div class="col-lg-4 col-md-6">
                                        <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr>
                                <h5 class="mb-3">Permissions</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle permissionTable bg-white" style="width: 100%;">
                                        <thead class="table-light">
                                        <tr>
                                            <th width="20%">Group</th>
                                            <th width="15%">
                                                <div class="form-check">
                                                    <input class="form-check-input grand_selectall" type="checkbox" id="grand_select">
                                                    <label class="form-check-label fw-bold" for="grand_select">Select All</label>
                                                </div>
                                            </th>
                                            <th>Available permissions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($permissions as $groupId => $groupPermissions)
                                            <tr>
                                                <td class="fw-medium text-capitalize">
                                                    {{ is_string($groupId) ? str_replace('_', ' ', $groupId) : 'General' }}
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input selectall" type="checkbox">
                                                        <label class="form-check-label">Select All</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        @forelse($groupPermissions as $permission)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input permissioncheckbox" id="perm_{{ $permission->id }}">
                                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        @empty
                                                            <span class="text-muted small">No permissions in this group</span>
                                                        @endforelse
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-end">
                                        <a href="{{ route('admin_panel.role.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary" id="submit_btn">Submit</button>
                                        <div id="submit_label" class="mt-2 small text-muted"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Form submission handling
            $('form[name=create]').on('submit', function(e) {
                const $btn = $('#submit_btn');
                const $label = $('#submit_label');

                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please wait...');
                $label.text('Processing your request...');
            });

            // Handle individual row select all
            $(".permissionTable").on('change', '.selectall', function () {
                let isChecked = $(this).is(':checked');
                $(this).closest('tr').find('.permissioncheckbox').prop('checked', isChecked);
                updateGrandSelectAll();
            });

            // Handle grand select all (all rows)
            $(".permissionTable").on('change', '.grand_selectall', function () {
                let isChecked = $(this).is(':checked');
                $('.selectall, .permissioncheckbox').prop('checked', isChecked);
            });

            // Handle individual permission checkbox click
            $(".permissionTable").on('change', '.permissioncheckbox', function () {
                let row = $(this).closest('tr');
                let allCheckedInRow = row.find('.permissioncheckbox').length === row.find('.permissioncheckbox:checked').length;
                row.find('.selectall').prop('checked', allCheckedInRow);
                updateGrandSelectAll();
            });

            function updateGrandSelectAll() {
                let totalCheckboxes = $('.permissioncheckbox').length;
                let totalChecked = $('.permissioncheckbox:checked').length;
                $('.grand_selectall').prop('checked', totalCheckboxes === totalChecked && totalCheckboxes > 0);
            }
        });
    </script>
@endpush
