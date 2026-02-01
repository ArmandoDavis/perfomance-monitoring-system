<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png')}}" type="image/png">

    @include('layouts.admin.head_css')
    @stack('styles')
</head>
<body>
@include('layouts.hod.topbar')
@include('layouts.hod.sidebar_backend')

<main class="main-wrapper">
    <div class="main-content">
        {{ \Diglactic\Breadcrumbs\Breadcrumbs::render() }}
        @include('includes.partials.alerts')
        @yield('content')
    </div>
</main>


<!--start overlay-->
<div class="overlay btn-toggle"></div>
<!--end overlay-->
@include('layouts.footer')

@include('includes.assets.shared_js_asset')
@include('layouts.admin.vendor_scripts')
@stack('plugins')
@stack('scripts')
</body>
</html>
