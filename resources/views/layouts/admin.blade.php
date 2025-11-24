<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Dashboard') - Bengkel Fajri</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <style>
        /* Base Theme */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a !important; /* Slate 950 */
            color: #cbd5e1;
        }
        
        /* Wrapper & Content Background */
        #wrapper, #content-wrapper, #content {
            background-color: #0f172a !important;
        }

        /* Sidebar (Darkest) */
        .sidebar {
            background-color: #020617 !important; /* Hampir Hitam */
            background-image: none !important;
            border-right: 1px solid #1e293b;
        }
        
        .sidebar .nav-item .nav-link span {
            font-family: 'Oswald', sans-serif;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .sidebar .nav-item.active .nav-link {
            color: #f97316 !important; /* Orange Accent */
            font-weight: bold;
        }
        
        .sidebar .nav-item.active .nav-link i {
            color: #f97316 !important;
        }

        /* Topbar */
        .topbar {
            background-color: #1e293b !important; /* Slate 800 */
            border-bottom: 1px solid #334155 !important;
        }

        /* Cards (Kotak-kotak Konten) */
        .card {
            background-color: #1e293b !important;
            border: 1px solid #334155 !important;
            color: #fff;
        }
        
        .card-header {
            background-color: #1e293b !important;
            border-bottom: 1px solid #334155 !important;
            color: #f97316 !important; /* Orange Title */
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Tables (Datatables) */
        table.dataTable tbody tr {
            background-color: #1e293b !important;
            color: #cbd5e1;
        }
        table.dataTable thead th {
            border-bottom: 2px solid #f97316 !important;
            color: #fff;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #334155 !important;
        }
        
        /* Inputs & Forms */
        .form-control {
            background-color: #0f172a;
            border: 1px solid #475569;
            color: #fff;
        }
        .form-control:focus {
            background-color: #0f172a;
            color: #fff;
            border-color: #f97316;
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.25);
        }

        /* Buttons */
        .btn-primary {
            background-color: #f97316 !important;
            border-color: #f97316 !important;
            color: #000 !important;
            font-weight: bold;
            text-transform: uppercase;
        }
        .btn-primary:hover {
            background-color: #ea580c !important;
        }

        /* Text Colors */
        .text-gray-800 { color: #f8fafc !important; }
        .text-gray-600 { color: #94a3b8 !important; }
        
        /* Scrollbar Keren */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: #f97316; }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        @include('layouts.partials.admin-sidebar')
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                @include('layouts.partials.admin-navbar')
                <div class="container-fluid">
                    @if(isset($header))
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-white font-weight-bold" style="font-family: 'Oswald', sans-serif; text-transform: uppercase;">
                                {{ $header }}
                            </h1>
                        </div>
                    @endif

                    @yield('content')

                </div>
                </div>
            <footer class="sticky-footer bg-transparent">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto text-gray-600">
                        <span>Copyright &copy; FajriGarage 2025</span>
                    </div>
                </div>
            </footer>
            </div>
        </div>
    <a class="scroll-to-top rounded" href="#page-top" style="background-color: #f97316;">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #1e293b; color: white;">
                <div class="modal-header" style="border-bottom: 1px solid #334155;">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah jika Anda siap mengakhiri sesi ini.</div>
                <div class="modal-footer" style="border-top: 1px solid #334155;">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
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