<div class="modal fade" id="auditDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('Audit Comparison (Old vs New)') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 border-end">
                        <p class="fw-bold text-danger small text-uppercase">{{ __('Previous Values') }}</p>
                        <pre id="oldValues" class="bg-light p-3 rounded small" style="max-height: 300px; overflow-y: auto;"></pre>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-bold text-success small text-uppercase">{{ __('New Values') }}</p>
                        <pre id="newValues" class="bg-light p-3 rounded small" style="max-height: 300px; overflow-y: auto;"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function viewAuditDetails(oldVal, newVal) {
            document.getElementById('oldValues').textContent = JSON.stringify(oldVal, null, 4) || 'N/A';
            document.getElementById('newValues').textContent = JSON.stringify(newVal, null, 4) || 'N/A';

            const modal = new bootstrap.Modal(document.getElementById('auditDetailModal'));
            modal.show();
        }
    </script>
@endpush
