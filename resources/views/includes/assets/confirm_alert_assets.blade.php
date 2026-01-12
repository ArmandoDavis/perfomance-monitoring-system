@push('scripts')
    <script>
        function formActionConfirmation(dataId, actionText = '', confirmColor = '#d33', cancelColor = '#3085d6') {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You want') }} " + actionText + ". {{ __('This action cannot be undone') }}",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: cancelColor,
                confirmButtonText: "{{ __('Yes, continue') }}",
                cancelButtonText: "{{ __('Cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('.confirm-form-' + dataId).submit();
                }
            });
        }
    </script>
@endpush
