@push('styles')
    <link href="{{ asset('/assets/teganas/plugins/toastr-notify/toastr.min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('/assets/teganas/plugins/toastr-notify/toastr.min.js') }}"></script>

    <script>
        // search task
        $(document).ready(function () {
            let $input = $('#taskSearchInput');
            let $results = $('#taskSearchResults');

            function searchUsers(query) {
                if (query.length < 2) {
                    $results.addClass('d-none').empty();
                    return;
                }

                {{--$.get("{{ route('admin_panel.task.search_task') }}", { q: query }, function (data) {--}}
                {{--    $results.empty();--}}
                {{--    if (data.length === 0) {--}}
                {{--        $results.append('<div class="list-group-item text-muted">{{__('No result found')}}</div>');--}}
                {{--    } else {--}}
                {{--        data.forEach(task => {--}}
                {{--            $results.append(--}}
                {{--                `<a href="/admin_panel/task/profile/${task.uid}" class="list-group-item list-group-item-action">--}}
                {{--                ${task.title}--}}
                {{--            </a>`--}}
                {{--            );--}}
                {{--        });--}}
                {{--    }--}}
                {{--    $results.removeClass('d-none');--}}
                {{--});--}}
            }

            // search on typing
            $input.on('keyup', function () {
                searchUsers($(this).val());
            });

            // search on button click
            $('#taskSearchBtn').on('click', function () {
                searchUsers($input.val());
            });

            // hide results when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#taskSearchForm').length) {
                    $results.addClass('d-none');
                }
            });
        });
    </script>
@endpush
