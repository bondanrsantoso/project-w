<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Dashboard - Mazer Admin Dashboard</title>

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/main/app.css') }}"
    >
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/main/app-dark.css') }}"
    >
    <link
        rel="shortcut icon"
        href="{{ asset('assets/images/logo/favicon.svg') }}"
        type="image/x-icon"
    >
    <link
        rel="shortcut icon"
        href="{{ asset('assets/images/logo/favicon.png') }}"
        type="image/png"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/shared/iconly.css') }}"
    >

    @yield('css')

    @yield('head-js')
</head>

<body id="body">
    <div id="app">
        @include('dashboard.layouts.sidebar')

        <div id="main">
            <header class="mb-3">
                <a
                    href="#"
                    class="burger-btn d-block d-xl-none"
                >
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                @yield('header')
            </div>

            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>


    @include('dashboard.layouts.scripts')
    @yield('scripts')
</body>

</html>
