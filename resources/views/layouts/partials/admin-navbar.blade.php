<style>
    /* Navbar Background Gelap */
    .navbar-custom {
        background-color: #1e293b !important;
        border-bottom: 1px solid #334155 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }
    /* Dropdown Menu */
    .dropdown-menu-dark-custom {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
    }
    /* Item Text */
    .dropdown-item-dark-custom {
        color: #cbd5e1 !important;
        transition: all 0.2s;
    }
    .dropdown-item-dark-custom:hover {
        background-color: #334155 !important;
        color: #fff !important;
        padding-left: 1.5rem;
    }
    /* Divider & Icon */
    .dropdown-divider-custom { border-top: 1px solid #334155 !important; }
    .dropdown-item-dark-custom i { color: #94a3b8 !important; }
    .dropdown-item-dark-custom:hover i { color: #fff !important; }
</style>

<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow navbar-custom">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-white-50">
        <i class="fa fa-bars"></i>
    </button>

    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block" style="border-color: #334155;"></div>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-300 small font-weight-bold">
                    {{ Auth::user()->name ?? 'User' }}
                </span>
                
                <div class="img-profile rounded-circle d-flex align-items-center justify-content-center font-weight-bold text-white" 
                     style="width: 32px; height: 32px; background-color: #4e73df;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in dropdown-menu-dark-custom"
                aria-labelledby="userDropdown">
                
                <div class="px-3 py-2 text-center border-bottom border-secondary mb-2">
                    <span class="small text-gray-500 d-block">Login sebagai:</span>
                    <span class="font-weight-bold text-white text-uppercase">{{ Auth::user()->role }}</span>
                </div>

                <a class="dropdown-item dropdown-item-dark-custom" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2"></i>
                    Profile Saya
                </a>
                
                {{-- [DIHAPUS] Menu Settings yang tidak berfungsi --}}

                {{-- Activity Log Shortcut (Khusus Admin) --}}
                @if(Auth::user()->role === 'admin')
                    <a class="dropdown-item dropdown-item-dark-custom" href="{{ route('admin.logs.index') }}">
                        <i class="fas fa-list fa-sm fa-fw mr-2"></i>
                        Activity Log
                    </a>
                @endif

                <div class="dropdown-divider dropdown-divider-custom"></div>
                
                <a class="dropdown-item dropdown-item-dark-custom text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>