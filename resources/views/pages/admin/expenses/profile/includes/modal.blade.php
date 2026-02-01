<div class="modal fade" id="previewImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h6 class="modal-title">{{ __('Expense Receipts Gallery') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="receiptCarousel" class="carousel slide carousel-fade" data-bs-ride="false">
                    <div class="carousel-inner" id="carouselItems">
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#receiptCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#receiptCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="modal-footer border-top-0 small text-muted justify-content-center">
                {{ __('Use arrows to navigate between multiple receipts') }}
            </div>
        </div>
    </div>
</div>
