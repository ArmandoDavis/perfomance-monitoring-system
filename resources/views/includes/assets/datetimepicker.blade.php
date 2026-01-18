@push('styles')
    <link rel="stylesheet" href="{{ asset('/assets/teganas/plugins/xdan/css/jquery.datetimepicker.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('/assets/teganas/plugins/xdan/js/jquery.datetimepicker.full.min.js') }}"> </script>
    <script>
        $(function () {
            jQuery('.datepicker2').datetimepicker({
                timepicker:false,
                format:'d-M-Y',
                weeks: true,
                dayOfWeekStart: 1,
                lazyInit: true,
                scrollInput: false
            });

            var today_date = new Date;
            var dd = today_date.getDate();
            var mm = today_date.getMonth() + 1; //January is 0!
            var yyyy = today_date.getFullYear();

            today_date = yyyy + '/' + mm + '/' + dd;

            jQuery('.datepicker_before_today').datetimepicker({
                timepicker:false,
                format:'d-M-Y',
                weeks: false,
                dayOfWeekStart: 1,
                lazyInit: true,
                scrollInput: false,
                maxDate: today_date,
            });

            jQuery('.datepicker_after_today').datetimepicker({
                timepicker:false,
                format:'d-M-Y',
                weeks: false,
                dayOfWeekStart: 1,
                lazyInit: true,
                scrollInput: false,
                minDate: today_date,
            });

            jQuery('.timepicker').datetimepicker({
                timepicker:true,
                datepicker:false,
                format:'H:i',
                weeks: false,
                dayOfWeekStart: 1,
                lazyInit: true,
                scrollInput: false,
            });

            jQuery('.datetime').datetimepicker({
                timepicker:true,
                datepicker:true,
                format:'Y-m-d H:i',
                weeks: true,
                dayOfWeekStart: 1,
                lazyInit: true,
                scrollInput: false,
            });
        });
    </script>
@endpush
