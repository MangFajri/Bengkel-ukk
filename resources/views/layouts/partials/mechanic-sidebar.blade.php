<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar"
    style="background-color: #020617 !important; background-image: none;">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-wrench" style="color: #f97316;"></i>
        </div>
        <div class="sidebar-brand-text mx-3" style="font-family: 'Oswald', sans-serif;">
            Mekanik<span style="color: #f97316;">Area</span>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Tugas
    </div>

    <!-- Nav Item - Daftar Pekerjaan -->
    <!-- PERBAIKAN: Kecualikan 'mechanic.jobs.history' agar tidak ikut aktif saat buka riwayat -->
    <li
        class="nav-item {{ (request()->routeIs('mechanic.jobs.*') && !request()->routeIs('mechanic.jobs.history')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('mechanic.jobs.index') }}">
            <i class="fas fa-fw fa-tools"></i>
            <span>Daftar Pekerjaan</span>
        </a>
    </li>

    <!-- Nav Item - Riwayat Service -->
    <!-- Ini sudah benar, hanya aktif jika rute spesifik 'mechanic.jobs.history' -->
    <li class="nav-item {{ request()->routeIs('mechanic.jobs.history') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('mechanic.jobs.history') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Service</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>