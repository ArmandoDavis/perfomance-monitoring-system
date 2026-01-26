@push('styles')
    <link href="{{ asset('/assets/teganas/plugins/toastr-notify/toastr.min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('/assets/teganas/plugins/toastr-notify/toastr.min.js') }}"></script>

    <script>
        let searchTimeout = null;

        function doGlobalSearch(query) {
            fetch(`{{ route('admin_panel.global.search') }}?q=` + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('global-search-results');
                    container.innerHTML = '';

                    if (!data.length) {
                        container.innerHTML = `<div class="text-muted small">No results found</div>`;
                        return;
                    }

                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'search-list-item d-flex align-items-center gap-3 cursor-pointer';
                        div.innerHTML = `
                            <div class="list-icon">
                                <i class="material-icons-outlined fs-5">
                                    ${item.type === 'task' ? 'task' : item.type === 'user' ? 'person' : 'apartment'}
                                </i>
                            </div>
                            <div>
                                <h5 class="mb-0 search-list-title">${item.title}</h5>
                                <small class="text-muted">${item.type}</small>
                            </div>
                        `;

                        div.onclick = () => {
                            window.location.href = item.url;
                        };

                        container.appendChild(div);
                    });
                });
        }

        // Desktop
        document.getElementById('global-search-input').addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            const q = this.value.trim();

            searchTimeout = setTimeout(() => {
                if (q.length >= 2) {
                    doGlobalSearch(q);
                }
            }, 300);
        });

        // Mobile
        document.getElementById('global-search-input-mobile').addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            const q = this.value.trim();

            searchTimeout = setTimeout(() => {
                if (q.length >= 2) {
                    doGlobalSearch(q);
                }
            }, 300);
        });
    </script>
@endpush
