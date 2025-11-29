@extends('layouts.customer')

@section('title', 'Riwayat Booking')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-white font-weight-bold">Riwayat Service</h1>
            <a href="{{ route('customer.transactions.create') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 mr-2"></i>Booking Baru
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 text-white mb-4" style="background-color: #1cc88a;">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Anda</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-gray-300" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-white" style="border-bottom: 2px solid #475569;">
                                <th>Tanggal</th>
                                <th>Mobil</th>
                                <th>Layanan</th>
                                <th>Status</th>
                                <th>Biaya</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr style="border-bottom: 1px solid #334155;">
                                    <td class="align-middle">
                                        <span
                                            class="text-white font-weight-bold">{{ $trx->created_at->format('d M Y') }}</span><br>
                                        <small class="text-gray-500">{{ $trx->code }}</small>
                                    </td>

                                    <td class="align-middle text-white">
                                        {{ $trx->vehicle->brand ?? '-' }} {{ $trx->vehicle->model ?? '' }}
                                        <br>
                                        <span
                                            class="badge badge-dark border border-secondary text-uppercase">{{ $trx->vehicle->plate_number ?? 'Hapus' }}</span>
                                    </td>

                                    <td class="align-middle">
                                        @if($trx->services->isEmpty())
                                            <span class="text-muted small text-italic">Tidak ada layanan tercatat</span>
                                        @else
                                            @foreach($trx->services as $service)
                                                <div class="small text-white">
                                                    <i class="fas fa-check-circle text-success mr-1"></i> {{ $service->name }}
                                                </div>
                                            @endforeach
                                        @endif
                                    </td>

                                    <td class="align-middle">
                                        @php
                                            // 1. Ambil DATA RELASI (Gunakan 'label' bukan 'name')
                                            // Jika relasi kosong (null), default ke objek kosong agar tidak error
                                            $statusObj = $trx->serviceStatus;

                                            // Tampilkan Label (Contoh: "Selesai", "Sedang Dikerjakan")
                                            $displayText = $statusObj->label ?? 'Pending';

                                            // Ambil Code untuk logika warna (Contoh: 'done', 'in_progress')
                                            $statusCode = $statusObj->code ?? 'default';

                                            // 2. LOGIKA WARNA (Berdasarkan 'code' di database)
                                            $badgeClass = match ($statusCode) {
                                                'booking', 'waiting' => 'warning', // Kuning
                                                'in_progress' => 'info',    // Biru
                                                'done' => 'success', // Hijau
                                                'cancelled', 'batal' => 'danger',  // Merah
                                                default => 'secondary' // Abu-abu
                                            };
                                        @endphp

                                        <span class="badge badge-{{ $badgeClass }} px-2 py-1 text-uppercase">
                                            {{ $displayText }}
                                        </span>
                                    </td>

                                    <td class="align-middle text-white font-weight-bold">
                                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center align-middle">
                                        <a href="{{ route('customer.transactions.show', $trx->id) }}"
                                            class="btn btn-primary btn-sm shadow-sm">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-gray-500">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                                        Belum ada riwayat service.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection