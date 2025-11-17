<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('customer.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-fw fa-user"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Area Pelanggan</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

   <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('customer.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    
     <li class="nav-item {{ request()->routeIs('customer.transactions.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('customer.transactions.index') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Servis</span>
        </a>
    </li>

    <!-- Nav Item - Kendaraan Saya -->
    <li class="nav-item {{ request()->routeIs('customer.vehicles.*') ? 'active' : '' }}">
        {{-- Perbarui link ini --}}
        <a class="nav-link" href="{{ route('customer.vehicles.index') }}">
            <i class="fas fa-fw fa-car"></i>
            <span>Kendaraan Saya</span>
        </a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>