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
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
        crossorigin="anonymous"
    >
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
    <script>
        const inputsWithPreview = document.querySelectorAll("input[type=file][data-preview-to]");

        for (const input of inputsWithPreview) {
            const previewTargets = document.querySelectorAll(input.dataset.previewTo);
            const fileReader = new FileReader();
            fileReader.addEventListener("loadend", e => {
                for (const target of previewTargets) {
                    target.src = fileReader.result;
                }
            });

            input.addEventListener("change", e => {
                if (input.files.length > 0) {
                    const file = input.files[0];
                    fileReader.readAsDataURL(file);
                }
            })
        }
    </script>
</body>

</html>
