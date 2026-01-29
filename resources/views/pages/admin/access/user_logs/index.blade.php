<section class="card mb-4 col-md-12">
    <div class="card-body">
        <br/>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover table-responsive-md" id="userLogsTable">
                    <thead>
                        <tr>
                            <th>{{__('label.username')}}</th>
                            <th>{{__('label.log_type')}}</th>
                            <th>{{__('label.browser')}}</th>
                            <th>{{__('label.device')}}</th>
                            <th>{{__('label.platform')}}</th>
                            <th>{{__('label.location')}}</th>
                            <th>{{__('label.created_at')}}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script type="text/javascript">
        $(function() {
            var url = "{{ url("/") }}";

            $('#userLogsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url : '{{ route('backend.user_logs.get_all_for_dt') }}',
                    type : 'get'
                },
                columns: [
                    { data: 'username', name: 'user_logs.username', orderable: false, searchable: true },
                    { data: 'log_type_name', name: 'log_type_name', orderable: true, searchable: true },
                    { data: 'browser', name: 'browser', orderable: false, searchable: true },
                    { data: 'device', name: 'device', orderable: false, searchable: true },
                    { data: 'platform', name: 'platform', orderable: false, searchable: true },
                    { data: 'location', name: 'location', orderable: false, searchable: true },
                    { data: 'created_at', name: 'created_at', orderable: false, searchable: true },
                ],
                fnRowCallback: function(nRow, aData) {
                    $(nRow).click(function() {
                        document.location.href = url + "/backend/user_logs/profile/" + aData['id'];
                    }).hover(function() {
                        $(this).css('cursor','pointer');
                    }, function() {
                        $(this).css('cursor','auto');
                    });
                }
            });
        });
    </script>
@endpush
