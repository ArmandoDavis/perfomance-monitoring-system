@push('scripts')
    <script src="{{ asset('/assets/teganas/plugins/maskmoney/js/maskmoney.min.js') }}"> </script>

    <script>
        $(function() {
            $('.money').maskMoney({
                precision : 2,
                allowZero : false,
                affixesStay : false
            });
        });
    </script>
@endpush
