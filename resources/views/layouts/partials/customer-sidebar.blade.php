<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #020617 !important; background-image: none;">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-car" style="color: #f97316;"></i>
        </div>
        <div class="sidebar-brand-text mx-3" style="font-family: 'Oswald', sans-serif;">
            Fajri<span style="color: #f97316;">Garage</span>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard Customer</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Layanan
    </div>

    <li class="nav-item {{ request()->routeIs('customer.transactions.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('customer.transactions.index') }}">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Booking Service</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('customer.vehicles.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('customer.vehicles.index') }}">
            <i class="fas fa-fw fa-car"></i>
            <span>Kendaraan Saya</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>