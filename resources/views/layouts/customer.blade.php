<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Customer Area') - FajriGarage</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a !important; color: #cbd5e1; }
        #wrapper, #content-wrapper, #content { background-color: #0f172a !important; }
        /* Sidebar */
        .sidebar { background-color: #020617 !important; border-right: 1px solid #1e293b; }
        .sidebar .nav-item .nav-link span { font-family: 'Oswald', sans-serif; letter-spacing: 1px; }
        .sidebar .nav-item.active .nav-link { color: #f97316 !important; font-weight: bold; }
        .sidebar .nav-item.active .nav-link i { color: #f97316 !important; }
        /* Components */
        .topbar { background-color: #1e293b !important; border-bottom: 1px solid #334155 !important; }
        .card { background-color: #1e293b !important; border: 1px solid #334155 !important; color: #fff; }
        .card-header { background-color: #1e293b !important; border-bottom: 1px solid #334155 !important; color: #f97316 !important; font-family: 'Oswald', sans-serif; text-transform: uppercase; }
        /* Tables */
        table.dataTable tbody tr { background-color: #1e293b !important; color: #cbd5e1; }
        table.dataTable thead th { border-bottom: 2px solid #f97316 !important; color: #fff; }
        .table-bordered td, .table-bordered th { border: 1px solid #334155 !important; }
        /* Buttons & Forms */
        .btn-primary { background-color: #f97316 !important; border-color: #f97316 !important; color: #000 !important; font-weight: bold; text-transform: uppercase; }
        .btn-primary:hover { background-color: #ea580c !important; }
        .form-control { background-color: #0f172a; border: 1px solid #475569; color: #fff; }
        .text-gray-800 { color: #f8fafc !important; }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.partials.customer-sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.partials.admin-navbar')

                <div class="container-fluid">
                    @if(isset($header))
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-white font-weight-bold font-display uppercase">{{ $header }}</h1>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </div>
            <footer class="sticky-footer bg-transparent"><div class="container my-auto"><div class="copyright text-center my-auto"><span>&copy; FajriGarage 2025</span></div></div></footer>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    @stack('scripts')
</body>
</html>