<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar"
    style="background-color: #1e293b; border-right: 1px solid #334155;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-wrench text-warning" style="font-size: 1.5rem;"></i>
        </div>
        <div class="sidebar-brand-text mx-3 text-white" style="letter-spacing: 1px;">
            BENGKEL <span class="text-warning font-weight-bold">PRO</span>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" style="border-color: #334155;">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt text-info"></i>
            <span class="font-weight-bold">Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" style="border-color: #334155;">

    <!-- Heading -->
    <div class="sidebar-heading text-gray-500">
        Manajemen Utama
    </div>

    <!-- Nav Item - Transaksi (Menu Utama) -->
    <li class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.transactions.index') }}">
            <i class="fas fa-fw fa-cash-register text-success"></i>
            <span class="font-weight-bold">Transaksi & Kasir</span>
        </a>
    </li>

    <!-- Nav Item - Kendaraan -->
    <li class="nav-item {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.vehicles.index') }}">
            <i class="fas fa-fw fa-car text-primary"></i>
            <span class="font-weight-bold">Data Kendaraan</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" style="border-color: #334155;">

    <!-- Heading -->
    <div class="sidebar-heading text-gray-500">
        Data Master
    </div>

    <!-- Nav Item - Collapse Menu Inventori -->
    <li
        class="nav-item {{ request()->routeIs('admin.spare-parts.*') || request()->routeIs('admin.services.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventory"
            aria-expanded="true" aria-controls="collapseInventory">
            <i class="fas fa-fw fa-boxes text-warning"></i>
            <span class="font-weight-bold">Inventori</span>
        </a>
        <div id="collapseInventory" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded border border-secondary" style="background-color: #0f172a;">
                <h6 class="collapse-header text-gray-400">Stok & Harga:</h6>
                <a class="collapse-item text-gray-300 {{ request()->routeIs('admin.spare-parts.*') ? 'active font-weight-bold text-white' : '' }}"
                    href="{{ route('admin.spare-parts.index') }}">Sparepart (Barang)</a>
                <a class="collapse-item text-gray-300 {{ request()->routeIs('admin.services.*') ? 'active font-weight-bold text-white' : '' }}"
                    href="{{ route('admin.services.index') }}">Jasa Service</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Collapse Menu Keuangan (BARU) -->
    <li class="nav-item {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFinance"
            aria-expanded="true" aria-controls="collapseFinance">
            <i class="fas fa-fw fa-wallet text-success"></i>
            <span class="font-weight-bold">Keuangan</span>
        </a>
        <div id="collapseFinance" class="collapse" aria-labelledby="headingFinance" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded border border-secondary" style="background-color: #0f172a;">
                <h6 class="collapse-header text-gray-400">Metode & Promo:</h6>
                <a class="collapse-item text-gray-300 {{ request()->routeIs('admin.payment-methods.*') ? 'active font-weight-bold text-white' : '' }}"
                    href="{{ route('admin.payment-methods.index') }}">Metode Pembayaran</a>
                {{-- Placeholder Promo (Bisa diaktifkan nanti) --}}
                {{-- <a class="collapse-item text-gray-300" href="#">Kode Promo (Soon)</a> --}}
            </div>
        </div>
    </li>

    <!-- Nav Item - Collapse Pengguna -->
    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="true"
            aria-controls="collapseUser">
            <i class="fas fa-fw fa-users-cog text-danger"></i>
            <span class="font-weight-bold">Pengguna & Akses</span>
        </a>
        <div id="collapseUser" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded border border-secondary" style="background-color: #0f172a;">
                <h6 class="collapse-header text-gray-400">Role User:</h6>
                <a class="collapse-item text-gray-300 {{ request()->routeIs('admin.users.*') ? 'active font-weight-bold text-white' : '' }}"
                    href="{{ route('admin.users.index') }}">Kelola User & Hak Akses</a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Log Aktivitas Sistem -->
    <li class="nav-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.logs.index') }}">
            <i class="fas fa-fw fa-history text-light"></i>
            <span class="font-weight-bold">Activity Log</span>
        </a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block" style="border-color: #334155;">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0 bg-secondary" id="sidebarToggle"></button>
    </div>

    <!-- Info Versi App -->
    <div class="sidebar-card d-none d-lg-flex mt-4 border-0" style="background-color: #0f172a;">
        <i class="fas fa-rocket text-success mb-2"></i>
        <p class="text-center mb-2 text-gray-400 small"><strong>Bengkel UKK</strong> v1.0<br>Siap Uji Kompetensi!</p>
    </div>

</ul>

{{-- CSS Tambahan Khusus Sidebar --}}
<style>
    .sidebar-dark .nav-item.active .nav-link {
        color: #fff !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
        border-left: 4px solid #4e73df;
    }

    .sidebar-dark .nav-item .nav-link:hover {
        color: #e2e8f0 !important;
        background-color: rgba(255, 255, 255, 0.05);
    }

    .collapse-item:hover {
        background-color: #334155 !important;
        color: #fff !important;
    }
</style>