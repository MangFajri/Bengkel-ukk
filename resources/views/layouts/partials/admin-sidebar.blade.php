<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Bengkel MasTopanz</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        Operasional
    </div>

    <!-- Menu Manajemen Service (Ganti yang lama dengan ini) -->
    <li class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTransactions"
            aria-expanded="true" aria-controls="collapseTransactions">
            <i class="fas fa-fw fa-clipboard-list" style="color: #f97316;"></i> <!-- Ikon Orange -->
            <span>Manajemen Service</span>
        </a>
        <div id="collapseTransactions" class="collapse {{ request()->routeIs('admin.transactions.*') ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu Transaksi:</h6>
                <a class="collapse-item {{ request()->routeIs('admin.transactions.index') ? 'active' : '' }}"
                    href="{{ route('admin.transactions.index') }}">Daftar Service</a>
                <a class="collapse-item {{ request()->routeIs('admin.transactions.create') ? 'active' : '' }}"
                    href="{{ route('admin.transactions.create') }}">Input Service Baru</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Master Data
    </div>

    <!-- Nav Item - Services -->
    <li class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.services.index') }}">
            <i class="fas fa-fw fa-concierge-bell"></i>
            <span>Jasa (Services)</span>
        </a>
    </li>

    <!-- Nav Item - Sparepart -->
    <li class="nav-item {{ request()->routeIs('admin.spare-parts.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.spare-parts.index') }}">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Sparepart</span>
        </a>
    </li>

    <!-- Nav Item - Manajemen Kendaraan -->
    <li class="nav-item {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.vehicles.index') }}">
            <i class="fas fa-fw fa-car"></i>
            <span>Manajemen Kendaraan</span>
        </a>
    </li>

    <!-- Nav Item - Manajemen Pengguna -->
    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Manajemen Pengguna</span>
        </a>
    </li>

    <!-- Nav Item - Metode Pembayaran -->
    <li class="nav-item {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.payment-methods.index') }}">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Metode Pembayaran</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>