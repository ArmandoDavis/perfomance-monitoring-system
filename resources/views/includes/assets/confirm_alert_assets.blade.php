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


        function submitStatusAction(url, action) {
            Swal.fire({
                title: `Confirm ${action}`,
                text: `Are you sure you want to mark this task as ${action}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f6c23e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, continue'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'PUT';

                    form.appendChild(token);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

@endpush
