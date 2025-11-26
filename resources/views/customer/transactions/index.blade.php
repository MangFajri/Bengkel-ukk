@extends('layouts.customer')

@section('title', 'Riwayat Booking')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white font-weight-bold font-display text-uppercase">Riwayat Service</h1>
        <a href="{{ route('customer.transactions.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-dark mr-2"></i>Booking Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 text-white mb-4" role="alert" style="background-color: #16a34a;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3 border-bottom border-secondary" style="background-color: #1e293b;">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Kendaraan</th>
                            <th>Status Service</th>
                            <th>Estimasi Biaya</th>
                            <th class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr>
                            <td>#{{ $trx->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->check_in_at)->format('d M Y') }}</td>
                            <td>
                                <div class="font-weight-bold text-white">{{ $trx->vehicle->plate_number }}</div>
                                <small class="text-gray-400">{{ $trx->vehicle->brand }} {{ $trx->vehicle->model }}</small>
                            </td>
                            <td>
                                <!-- Badge Status Warna-Warni -->
                                @php
                                    $statusColor = match($trx->service_status_id) {
                                        1 => 'badge-success', // Done/Booking (sesuaikan id di seeder kamu)
                                        2 => 'badge-warning', // Waiting
                                        3 => 'badge-info',    // Process
                                        4 => 'badge-primary', // Done
                                        5 => 'badge-danger',  // Cancelled
                                        default => 'badge-secondary'
                                    };
                                    // Ambil label dari relasi jika ada, atau fallback manual
                                    $statusLabel = $trx->serviceStatus->label ?? 'Unknown'; 
                                @endphp
                                <span class="badge {{ $statusColor }} px-2 py-1">{{ $statusLabel }}</span>
                            </td>
                            <td class="text-white">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('customer.transactions.show', $trx->id) }}" class="btn btn-info btn-circle btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-3x text-gray-600 mb-3"></i>
                                <p class="text-gray-500">Belum ada riwayat service.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection