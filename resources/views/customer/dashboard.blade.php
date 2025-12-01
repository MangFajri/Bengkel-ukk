@extends('layouts.customer')

@section('title', 'Dashboard Saya')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-white font-weight-bold">Halo, {{ Auth::user()->name }}! 👋</h1>
            <a href="{{ route('customer.vehicles.create') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-car fa-sm text-white-50 mr-1"></i> Tambah Mobil
            </a>
        </div>

        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2"
                    style="background-color: #1e293b; border: 1px solid #334155; border-left: 4px solid #4e73df !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Kendaraan Saya</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $totalVehicles }} Unit</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-car fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2"
                    style="background-color: #1e293b; border: 1px solid #334155; border-left: 4px solid #f6c23e !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Sedang Diservice</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $activeServices }} Proses</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tools fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2"
                    style="background-color: #1e293b; border: 1px solid #334155; border-left: 4px solid #1cc88a !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Biaya Service</div>
                                <div class="h5 mb-0 font-weight-bold text-white">Rp
                                    {{ number_format($totalSpent, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                        style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Mobil</h6>
                        <a href="{{ route('customer.vehicles.index') }}" class="small text-primary">Lihat Semua &rarr;</a>
                    </div>
                    <div class="card-body">
                        @if($myVehicles->isEmpty())
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-car fa-3x text-gray-600"></i>
                                </div>
                                <p class="text-gray-400 mb-3">Belum ada kendaraan terdaftar.</p>
                                <a href="{{ route('customer.vehicles.create') }}" class="btn btn-sm btn-primary">Daftarkan
                                    Sekarang</a>
                            </div>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($myVehicles as $car)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0"
                                        style="background-color: transparent; border-bottom: 1px solid #334155;">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3 bg-primary rounded-circle d-flex align-items-center justify-content-center text-white"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-car"></i>
                                            </div>
                                            <div>
                                                <span class="font-weight-bold text-white">{{ $car->brand }} {{ $car->model }}</span>
                                                <br>
                                                <small class="text-gray-400">{{ $car->plate_number }}</small>
                                            </div>
                                        </div>
                                        <span class="badge badge-secondary">{{ $car->color ?? 'Warna -' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                        style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                        <h6 class="m-0 font-weight-bold text-primary">Riwayat Service</h6>
                    </div>
                    <div class="card-body">
                        @if($recentTransactions->isEmpty())
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-clipboard-list fa-3x text-gray-600 mb-3"></i>
                                <p>Belum ada riwayat service.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless text-white" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-gray-400" style="border-bottom: 1px solid #334155;">
                                            <th class="pb-2">Tanggal</th>
                                            <th class="pb-2">Mobil</th>
                                            <th class="pb-2 text-right">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentTransactions as $trx)
                                            <tr style="border-bottom: 1px solid #334155;">
                                                <td class="py-3">{{ $trx->created_at->format('d M Y') }}</td>
                                                <td class="py-3 font-weight-bold">{{ $trx->vehicle->plate_number ?? '-' }}</td>
                                                {{-- PERBAIKAN DI BAGIAN TABLE BODY --}}
                                                <td class="py-3 text-right">
                                                    @php
                                                        // Pakai Null Coalescing (??) biar gak crash kalau relasi hilang
                                                        $statusName = $trx->serviceStatus->name ?? 'Unknown';
                                                        $status = strtolower($statusName);

                                                        $badge = 'secondary';
                                                        if (str_contains($status, 'selesai'))
                                                            $badge = 'success';
                                                        elseif (str_contains($status, 'pending'))
                                                            $badge = 'warning';
                                                        elseif (str_contains($status, 'kerja'))
                                                            $badge = 'info';
                                                        elseif (str_contains($status, 'batal'))
                                                            $badge = 'danger';
                                                    @endphp
                                                    <span class="badge badge-{{ $badge }}">{{ $statusName }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection