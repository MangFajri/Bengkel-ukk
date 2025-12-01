@extends('layouts.customer')

@section('title', 'Dashboard Member')

@section('content')

    {{-- HEADER: GREETING & ACTION --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="h3 mb-1 text-gray-100 font-weight-bold">Halo, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-gray-400">Pantau kesehatan kendaraan Anda di sini.</p>
        </div>
        <div class="col-md-4 text-md-right">
            <a href="{{ route('customer.transactions.create') }}" class="btn btn-warning btn-lg shadow-sm font-weight-bold text-dark">
                <i class="fas fa-calendar-plus mr-2"></i> Booking Service
            </a>
        </div>
    </div>

    {{-- SECTION 1: LIVE TRACKING (HANYA MUNCUL JIKA ADA SERVICE AKTIF) --}}
    @if($activeTransaction)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-left-danger" style="background-color: #1e293b; border: 1px solid #334155; border-left-width: 4px !important;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-danger text-uppercase">
                        <i class="fas fa-satellite-dish mr-2 fa-spin"></i> Live Tracking Service
                    </h6>
                    <a href="{{ route('customer.transactions.show', $activeTransaction->id) }}" class="btn btn-sm btn-outline-light">Lihat Detail</a>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <div class="h4 font-weight-bold text-white">{{ $activeTransaction->vehicle->plate_number }}</div>
                            <div class="small text-gray-400">{{ $activeTransaction->vehicle->brand }} {{ $activeTransaction->vehicle->model }}</div>
                        </div>
                        <div class="col-md-9">
                            {{-- LOGIC PROGRESS BAR --}}
                            @php
                                $statusId = $activeTransaction->service_status_id;
                                $progress = 10; 
                                $color = 'secondary';
                                $text = 'Menunggu Konfirmasi';

                                // Sesuaikan ID status dengan database kamu
                                if($statusId == 1) { $progress = 25; $color = 'warning'; $text = 'Booking Dikonfirmasi (Antri)'; } // Booking
                                elseif($statusId == 2) { $progress = 10; $color = 'secondary'; $text = 'Menunggu Persetujuan'; } // Menunggu
                                elseif($statusId == 3) { $progress = 75; $color = 'primary'; $text = 'Sedang Dikerjakan Mekanik'; } // Proses
                            @endphp

                            <div class="mb-2 d-flex justify-content-between small font-weight-bold text-gray-300">
                                <span>Status: <span class="text-{{ $color }}">{{ $text }}</span></span>
                                <span>{{ $progress }}%</span>
                            </div>
                            <div class="progress" style="height: 20px; background-color: #0f172a;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $color }}" 
                                     role="progressbar" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- SECTION 2: STATISTIK CARDS --}}
    <div class="row">
        {{-- Card 1: Kendaraan --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Garasi Saya</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $totalVehicles }} Mobil</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Belanja --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Service (Lunas)</div>
                            <div class="h5 mb-0 font-weight-bold text-white">Rp {{ number_format($totalSpent, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Service Terakhir --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Service Terakhir</div>
                            <div class="font-weight-bold text-white">
                                @if($latestTransaction)
                                    #{{ $latestTransaction->id }} 
                                    <span class="badge badge-light ml-1">{{ $latestTransaction->serviceStatus->label ?? '-' }}</span>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-history fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: DUA KOLOM (MOBIL & HISTORY) --}}
    <div class="row">
        
        {{-- KOLOM KIRI: LIST MOBIL --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-white">Mobil Terdaftar</h6>
                    <a href="{{ route('customer.vehicles.index') }}" class="small text-primary">Lihat Semua &rarr;</a>
                </div>
                <div class="card-body p-0">
                    @if($myVehicles->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-gray-400 mb-3">Belum ada mobil.</p>
                            <a href="{{ route('customer.vehicles.create') }}" class="btn btn-sm btn-primary">Tambah Mobil</a>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($myVehicles as $car)
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-bottom: 1px solid #334155;">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 bg-dark rounded-circle d-flex align-items-center justify-content-center text-gray-300 border border-secondary" style="width: 40px; height: 40px;">
                                            <i class="fas fa-car"></i>
                                        </div>
                                        <div>
                                            <span class="font-weight-bold text-white">{{ $car->plate_number }}</span><br>
                                            <small class="text-gray-400">{{ $car->brand }} {{ $car->model }}</small>
                                        </div>
                                    </div>
                                    <span class="badge badge-secondary">{{ $car->year }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: RIWAYAT SERVICE --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-white">Riwayat Service</h6>
                </div>
                <div class="card-body">
                    @if($recentTransactions->isEmpty())
                        <div class="text-center py-4 text-gray-500">
                            <p>Belum ada riwayat transaksi.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless text-gray-300" width="100%">
                                <thead>
                                    <tr class="text-gray-500 uppercase text-xs border-bottom border-secondary">
                                        <th class="pb-2">Tanggal</th>
                                        <th class="pb-2">Mobil</th>
                                        <th class="pb-2 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $trx)
                                        <tr class="border-bottom border-secondary" style="border-color: #334155 !important;">
                                            <td class="py-3 align-middle">{{ $trx->created_at->format('d/m/y') }}</td>
                                            <td class="py-3 align-middle">
                                                <span class="text-white font-weight-bold">{{ $trx->vehicle->plate_number ?? '-' }}</span>
                                            </td>
                                            <td class="py-3 text-right align-middle">
                                                @php
                                                    $lbl = strtolower($trx->serviceStatus->label ?? '');
                                                    $cls = 'secondary';
                                                    if(str_contains($lbl, 'selesai')) $cls = 'success';
                                                    elseif(str_contains($lbl, 'kerja')) $cls = 'info';
                                                    elseif(str_contains($lbl, 'booking')) $cls = 'warning';
                                                @endphp
                                                <span class="badge badge-{{ $cls }}">{{ $trx->serviceStatus->label ?? '-' }}</span>
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
@endsection