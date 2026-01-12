@push('styles')
    <link href="{{ asset('/assets/teganas/plugins/datatables/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet">

    <!--datatable responsive css-->
    <link href="{{ asset('/assets/teganas/plugins/datatables/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <style>
        div.dataTables_filter input { width: 380px !important }
        tr.odd:hover {
            background-color: #edefff;
        }
        tr.even:hover {
            background-color: #d5dcd1;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ URL::asset('/assets/teganas/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/teganas/plugins/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/teganas/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/teganas/plugins/datatables/js/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/teganas/plugins/datatables/js/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/teganas/plugins/datatables/js/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/teganas/plugins/datatables/js/buttons/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/teganas/plugins/datatables/js/buttons/vfs_fonts.js') }}"></script>

    <script src="{{ URL::asset('/assets/teganas/plugins/datatables/js/buttons/jszip.min.js') }}"></script>
    <script>
        const screen_width = window.screen.width * window.devicePixelRatio;

        if(screen_width <= 700){
            $('.table_responsive').addClass('table-responsive');
        }
    </script>
@endpush
