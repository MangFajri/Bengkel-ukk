@extends('layouts.admin')

@section('title', 'Manajemen Transaksi')

@section('content')
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white font-weight-bold font-display text-uppercase">Daftar Booking & Service</h1>
        <!-- Tombol Tambah Manual (Opsional) -->
        {{-- <a href="{{ route('admin.transactions.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-dark mr-2"></i>Input Transaksi Manual
        </a> --}}
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 text-white mb-4" style="background-color: #16a34a;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Transaksi -->
    <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
            <h6 class="m-0 font-weight-bold text-primary">Semua Data Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Masuk</th>
                            <th>Customer</th>
                            <th>Kendaraan</th>
                            <th>Mekanik</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr>
                            <td>#{{ $trx->id }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($trx->check_in_at)->format('d/m/Y H:i') }}
                                <br>
                                <small class="text-gray-400">{{ \Carbon\Carbon::parse($trx->check_in_at)->diffForHumans() }}</small>
                            </td>
                            <td class="text-white font-weight-bold">
                                {{ $trx->customer->name }}
                            </td>
                            <td>
                                <div class="badge badge-light text-uppercase">{{ $trx->vehicle->plate_number }}</div>
                                <div class="small mt-1">{{ $trx->vehicle->brand }} {{ $trx->vehicle->model }}</div>
                            </td>
                            <td>
                                @if($trx->mechanic)
                                    <span class="text-info"><i class="fas fa-wrench mr-1"></i> {{ $trx->mechanic->name }}</span>
                                @else
                                    <span class="text-danger font-italic small">Belum Ditunjuk</span>
                                @endif
                            </td>
                            <td>
                                <!-- Logic Warna Status Service -->
                                @php
                                    $svcColor = match($trx->service_status_id) {
                                        1 => 'badge-success', 2 => 'badge-warning', 3 => 'badge-info', 4 => 'badge-primary', 5 => 'badge-danger', default => 'badge-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $svcColor }} d-block mb-1">{{ $trx->serviceStatus->label ?? '-' }}</span>
                                
                                <!-- Logic Warna Status Bayar -->
                                @php
                                    $payColor = match($trx->payment_status_id) {
                                        1 => 'text-success', 2 => 'text-warning', 3 => 'text-danger', default => 'text-secondary'
                                    };
                                    $payLabel = $trx->paymentStatus->label ?? '-';
                                @endphp
                                <small class="font-weight-bold {{ $payColor }}">
                                    <i class="fas fa-money-bill-wave mr-1"></i> {{ $payLabel }}
                                </small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.transactions.edit', $trx->id) }}" class="btn btn-primary btn-sm" title="Kelola / Proses">
                                    <i class="fas fa-edit"></i> Proses
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-gray-500">Tidak ada data transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Aktifkan Datatable
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[ 0, "desc" ]] // Urutkan ID terbesar (terbaru)
        });
    });
</script>
@endpush