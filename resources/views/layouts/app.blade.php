<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')

    <title>@yield('title')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Material Icons --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

    <style>
        body {
            background: #f5f5f5;
        }

        /* Sidebar */
        .sidebar-custom {
            position: fixed;
            width: 260px;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1500;
            transition: 0.3s ease;
        }

        /* Wrapper kanan */
        .main-wrapper {
            margin-left: 260px;
            padding: 20px;
            transition: 0.3s ease;
        }

        /* Top bar */
        .mdc-top-app-bar {
            background: white;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            margin-left: 260px;
        }

        /* MOBILE MODE */
        @media (max-width: 768px) {
            .sidebar-custom {
                left: -260px;
            }

            .sidebar-custom.active {
                left: 0;
            }

            .main-wrapper {
                margin-left: 0;
            }
        }

        /* Overlay */
        #overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: none;
            z-index: 1400;
        }

        #overlay.active {
            display: block;
        }
    </style>
</head>

<body>

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Toggle Button --}}
    <button id="sidebarToggle"
        class="btn btn-light d-md-none position-fixed"
        style="top:15px; left:15px; z-index:2000;">
        <span class="material-icons">menu</span>
    </button>

    <div id="overlay"></div>

    {{-- Content --}}
    <div class="main-wrapper">
        @yield('content')
    </div>

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // dropdown menu
        document.querySelectorAll('.sidebar-dropdown-toggle').forEach(function(btn) {
            btn.addEventListener('click', function() {
                this.parentElement.classList.toggle('active');
            });
        });

        // sidebar toggle
        const sidebar = document.querySelector('.sidebar-custom');
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('overlay');

        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', function () {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    </script>

</body>
</html>
