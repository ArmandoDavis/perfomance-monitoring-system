@push('styles')
    <link rel="stylesheet" href="{{ asset('/assets/teganas/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/teganas/select2/css/select2-bootstrap4.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('/assets/teganas/select2/js/select2.min.js') }}"> </script>

    <script>
        // Function to refresh select2 dropdowns
        function refreshSelect2(selectId, url, valueField = 'uid', textField = 'name') {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    var $select = $('#' + selectId);
                    $select.empty();
                    $select.append($('<option>', {
                        value: '',
                        text: "{{ trans('Select loading') }}"
                    }));

                    $.each(data, function(key, value) {
                        $select.append($('<option>', {
                            value: value[valueField],
                            text: value[textField]
                        }));
                    });

                    $select.trigger('change');
                }
            });
        }
    </script>
@endpush
