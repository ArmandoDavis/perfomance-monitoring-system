@extends('layouts.admin.app')
@section('title', __('Expense profile'))
@include('includes.assets.sweetalert_assets')
@include('includes.assets.confirm_alert_assets')

@section('content')
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#general_tab" role="tab" aria-selected="true">
                        {{ __('General Info') }}
                    </a>
                </li>
            </ul>

            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="general_tab" role="tabpanel">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end flex-wrap gap-2">
                                @if(!$expense->approved_at)
                                    <a href="{{ route('admin_panel.expenses.edit', $expense->uuid) }}" class="btn btn-sm btn-primary mb-2 mr-2">
                                        <i class="fas fa-edit me-1"></i> {{ __('Edit') }}
                                    </a>

                                    <form action="{{ route('admin_panel.expenses.approve', $expense->uuid) }}" method="POST" class="d-none confirm-form-approve-{{ $expense->uuid }}">
                                        @csrf
                                        @method('PUT')
                                    </form>

                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger mb-2 me-2" onclick="formActionConfirmation('approve-{{ $expense->uuid }}', '{{ __('Approve') }}' )">
                                        {{ __('Approve') }}
                                    </a>
                                @endif

                                <a href="{{ route('admin_panel.expenses.index') }}" class="btn btn-sm btn-dark mb-2 mr-2" >
                                    <i class="fas fa-closed-captioning me-1"></i> {{ __('Close') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @include('pages.admin.expenses.profile.includes.general_info')
                </div>
            </div>
        </div>
    </div>

    @include('pages.admin.expenses.profile.includes.modal')
@endsection

@push('scripts')
    <script>
        function previewImage(activeImageUrl) {
            const allImages = Array.from(document.querySelectorAll('.receipt-img-link')).map(el => el.dataset.url);
            const carouselInner = document.getElementById('carouselItems');

            carouselInner.innerHTML = '';

            allImages.forEach((url, index) => {
                const isActive = (url === activeImageUrl) ? 'active' : '';
                carouselInner.innerHTML += `
                <div class="carousel-item ${isActive}">
                    <img src="${url}" class="d-block w-100 rounded-bottom" style="max-height: 500px; object-fit: contain; background: #f8f9fa;">
                </div>
            `;
            });

            const previewModal = new bootstrap.Modal(document.getElementById('previewImageModal'));
            previewModal.show();
        }
    </script>
@endpush
