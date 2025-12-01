@extends('layouts.admin')

@section('title', 'Daftar Transaksi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-100 font-weight-bold border-left-primary pl-3">Kelola Transaksi Service</h1>
        <a href="{{ route('admin.transactions.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-2"></i> Transaksi Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-left-success shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-left-danger shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4 border-bottom-primary" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
            style="background-color: #1e293b; border-bottom: 1px solid #334155;">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list-alt mr-1"></i> Data Semua Transaksi
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- TABLE DARK MODE --}}
                <table class="table table-hover text-gray-300" id="dataTable" width="100%" cellspacing="0"
                    style="color: #e2e8f0;">
                    <thead style="background-color: #0f172a; color: #fff;">
                        <tr>
                            <th width="5%" style="border-bottom: 2px solid #334155; border-top: none;">No</th>
                            <th width="12%" style="border-bottom: 2px solid #334155; border-top: none;">Kode/ID</th>
                            <th width="15%" style="border-bottom: 2px solid #334155; border-top: none;">Tanggal</th>
                            <th style="border-bottom: 2px solid #334155; border-top: none;">Pelanggan</th>
                            <th style="border-bottom: 2px solid #334155; border-top: none;">Kendaraan</th>
                            <th style="border-bottom: 2px solid #334155; border-top: none;">Mekanik</th>
                            <th class="text-center" style="border-bottom: 2px solid #334155; border-top: none;">Status Service</th>
                            <th class="text-center" style="border-bottom: 2px solid #334155; border-top: none;">Status Bayar</th>
                            <th class="text-right" style="border-bottom: 2px solid #334155; border-top: none;">Total</th>
                            <th width="10%" class="text-center" style="border-bottom: 2px solid #334155; border-top: none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr style="border-bottom: 1px solid #334155;">
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">
                                    <span class="font-weight-bold text-primary">#{{ $transaction->id }}</span>
                                </td>
                                <td class="align-middle text-gray-400" style="font-size: 0.85rem;">
                                    <div style="line-height: 1.2;">
                                        {{-- Tampilkan Tanggal --}}
                                        <div class="font-weight-bold text-gray-300">
                                            {{ $transaction->created_at->translatedFormat('d M Y') }}
                                        </div>
                                        {{-- Tampilkan Jam (di baris bawah biar rapi) --}}
                                        <div class="text-info">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $transaction->created_at->format('H:i') }} WIB
                                        </div>
                                    </div>
                                </td>

                                {{-- Pelanggan --}}
                                <td class="align-middle">
                                    <div class="font-weight-bold text-white">
                                        {{ $transaction->customer->name ?? 'Umum/Terhapus' }}</div>
                                </td>

                                {{-- Kendaraan --}}
                                <td class="align-middle">
                                    @if($transaction->vehicle)
                                        <div class="badge badge-secondary border border-gray-600 text-white font-weight-bold px-2">
                                            {{ $transaction->vehicle->plate_number }}
                                        </div>
                                        <div class="small text-gray-400 mt-1">
                                            {{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }}
                                        </div>
                                    @else
                                        <span class="text-muted font-italic">-</span>
                                    @endif
                                </td>

                                {{-- Mekanik --}}
                                <td class="align-middle">
                                    @if($transaction->mechanic)
                                        <span class="text-gray-300"><i class="fas fa-user-cog mr-1 text-info"></i>
                                            {{ $transaction->mechanic->name }}</span>
                                    @else
                                        <span class="badge badge-dark border border-secondary text-gray-500">Belum Ada</span>
                                    @endif
                                </td>

                                {{-- Status Service --}}
                                <td class="align-middle text-center">
                                    @php
                                        $statusName = strtolower($transaction->serviceStatus->label ?? 'unknown');
                                        [$badgeColor, $icon] = match (true) {
                                            str_contains($statusName, 'selesai') => ['success', 'check-circle'],
                                            str_contains($statusName, 'kerja') => ['info', 'tools'],
                                            str_contains($statusName, 'proses') => ['primary', 'cogs'],
                                            str_contains($statusName, 'tunggu') => ['warning', 'clock'],
                                            str_contains($statusName, 'pending') => ['warning', 'hourglass-start'],
                                            str_contains($statusName, 'batal') => ['danger', 'times-circle'],
                                            default => ['secondary', 'question-circle']
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $badgeColor }} px-2 py-1 shadow-sm">
                                        <i class="fas fa-{{ $icon }} mr-1"></i> {{ $transaction->serviceStatus->label ?? '-' }}
                                    </span>
                                </td>

                                {{-- Status Pembayaran & NOTIFIKASI BUKTI --}}
                                <td class="align-middle text-center">
                                    @if($transaction->payment_status_id == 1)
                                        <span class="badge badge-success px-2 py-1 border border-success">
                                            <i class="fas fa-check mr-1"></i> LUNAS
                                        </span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1 border border-danger">
                                            <i class="fas fa-times mr-1"></i> BELUM
                                        </span>
                                        
                                        {{-- [FITUR BARU] Tombol Cek Bukti jika ada upload tapi belum lunas --}}
                                        @if($transaction->payment_proof)
                                            <div class="mt-2">
                                                <a href="{{ route('admin.transactions.edit', $transaction->id) }}" 
                                                   class="btn btn-sm btn-danger font-weight-bold py-0 shadow-sm pulse-button" 
                                                   style="font-size: 0.7rem; border: 1px solid #ff6b6b;">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> Cek Bukti
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </td>

                                <td class="align-middle text-right font-weight-bold text-success">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </td>

                                <td class="align-middle text-center">
                                    <div class="dropdown no-arrow">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle border-0" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false" style="background-color: #334155;">
                                            <i class="fas fa-ellipsis-v text-white"></i>
                                        </button>
                                        <div class="dropdown-menu shadow animated--fade-in" aria-labelledby="dropdownMenuButton"
                                            style="background-color: #1e293b; border: 1px solid #334155;">
                                            <div class="dropdown-header text-gray-400">Aksi:</div>

                                            <a class="dropdown-item text-warning"
                                                href="{{ route('admin.transactions.edit', $transaction->id) }}">
                                                <i class="fas fa-edit fa-sm fa-fw mr-2"></i> Edit Detail
                                            </a>

                                            @if($transaction->payment_status_id == 1)
                                                <a class="dropdown-item text-info"
                                                    href="{{ route('admin.transactions.print', $transaction->id) }}"
                                                    target="_blank">
                                                    <i class="fas fa-print fa-sm fa-fw mr-2"></i> Cetak Struk
                                                </a>
                                            @else
                                                <a class="dropdown-item disabled text-gray-600" href="#" tabindex="-1"
                                                    aria-disabled="true">
                                                    <i class="fas fa-print fa-sm fa-fw mr-2"></i> Cetak (Belum Lunas)
                                                </a>
                                            @endif

                                            <div class="dropdown-divider" style="border-top: 1px solid #334155;"></div>

                                            <form action="{{ route('admin.transactions.destroy', $transaction->id) }}"
                                                method="POST" class="d-inline block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Yakin hapus transaksi ini? Stok akan dikembalikan.');">
                                                    <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-gray-500 py-5">
                                    <img src="{{ asset('img/undraw_empty.svg') }}" alt="Empty"
                                        style="height: 100px; opacity: 0.5;" class="mb-3">
                                    <p class="mb-0">Belum ada data transaksi hari ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4 d-flex justify-content-end">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    {{-- Style Tambahan untuk animasi pulse tombol Cek Bukti --}}
    <style>
        .pulse-button {
            animation: pulse-red 2s infinite;
        }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(231, 74, 59, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(231, 74, 59, 0); }
            100% { box-shadow: 0 0 0 0 rgba(231, 74, 59, 0); }
        }
    </style>
@endsection