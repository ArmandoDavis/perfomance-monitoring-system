@extends('layouts.admin.app')
@section('title', 'Edit Role')
@section('content')
    <div class="container-fluid">
        <div id="content">
            <form action="{{ route('admin_panel.role.update', $role->uuid) }}" method="POST" id="update" name="edit" autocomplete="off" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <input type="hidden" name="resource_id" value="{{ $role->id }}">
                <input type="hidden" name="action_type" value="2">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow-sm">
                            <div class="card-body">

                                <div class="row mb-4">
                                    <div class="col-lg-4 col-md-6">
                                        <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                <h5 class="mb-3">Permissions</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle permissionTable bg-white">
                                        <thead class="table-light">
                                        <tr>
                                            <th width="20%">Group</th>
                                            <th width="15%">
                                                <div class="form-check">
                                                    <input class="form-check-input grand_selectall" type="checkbox" id="grand_select">
                                                    <label class="form-check-label fw-bold" for="grand_select">Select All</label>
                                                </div>
                                            </th>
                                            <th>Available Permissions</th>
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
                                                        <label class="form-check-label text-nowrap">Select All</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        @forelse($groupPermissions as $permission)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                                       class="form-check-input permissioncheckbox"
                                                                       id="perm_{{ $permission->id }}"
                                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
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
                                        <a href="{{ route('admin_panel.role.profile', $role->uuid) }}" class="btn btn-dark me-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary px-4" id="submit_btn">Update Role</button>
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
            // Form submission logic
            $('#update').on('submit', function() {
                const $btn = $('#submit_btn');
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Please wait...');
            });

            $(document).ready(function () {
                // Initialize checkboxes state
                initializeCheckBoxes();

                // Row Select All
                $(".permissionTable").on('change', '.selectall', function () {
                    let isChecked = $(this).is(':checked');
                    $(this).closest('tr').find('.permissioncheckbox').prop('checked', isChecked);
                    updateGrandSelectAll();
                });

                // Grand Select All
                $(".permissionTable").on('change', '.grand_selectall', function () {
                    let isChecked = $(this).is(':checked');
                    $('.selectall, .permissioncheckbox').prop('checked', isChecked);
                });

                // Individual Checkbox Click
                $(".permissionTable").on('change', '.permissioncheckbox', function () {
                    updateRowSelectAll($(this).closest('tr'));
                    updateGrandSelectAll();
                });

                function initializeCheckBoxes() {
                    $('.permissionTable tbody tr').each(function() {
                        updateRowSelectAll($(this));
                    });
                    updateGrandSelectAll();
                }

                function updateRowSelectAll($row) {
                    let total = $row.find('.permissioncheckbox').length;
                    let checked = $row.find('.permissioncheckbox:checked').length;
                    $row.find('.selectall').prop('checked', total === checked && total > 0);
                }

                function updateGrandSelectAll() {
                    let total = $('.permissioncheckbox').length;
                    let checked = $('.permissioncheckbox:checked').length;
                    $('.grand_selectall').prop('checked', total === checked && total > 0);
                }
            });
        });
    </script>
@endpush
