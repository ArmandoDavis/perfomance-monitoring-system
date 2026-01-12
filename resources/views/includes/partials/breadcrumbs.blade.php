@unless ($breadcrumbs->isEmpty())
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">@yield('title')</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if (!is_null($breadcrumb->url) && !$loop->last)
                            <li class="breadcrumb-item">
                                <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb->title }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>

        <div class="ms-auto">
            @yield('breadcrumb-action')
        </div>
    </div>
@endunless
