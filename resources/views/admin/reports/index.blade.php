@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Pendapatan</h1>
    </div>

    {{-- Filter Date Range --}}
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-body">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="form-inline">
                <label class="mr-2">Dari:</label>
                <input type="date" name="start_date" class="form-control mr-3" value="{{ $startDate }}">
                
                <label class="mr-2">Sampai:</label>
                <input type="date" name="end_date" class="form-control mr-3" value="{{ $endDate }}">
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter fa-sm"></i> Filter Laporan
                </button>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Omzet (Revenue)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalRevenue) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Estimasi Modal Barang (HPP)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalCost) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Laba Bersih (Estimasi)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($netProfit) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi Lunas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Nota</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td>{{ $trx->created_at->format('d-m-Y H:i') }}</td>
                                <td>#{{ $trx->id }}</td>
                                <td>{{ $trx->customer->name ?? 'Guest' }}</td>
                                <td>{{ $trx->vehicle->model ?? '-' }} ({{ $trx->vehicle->plate_number ?? '-' }})</td>
                                <td class="text-right font-weight-bold">Rp {{ number_format($trx->total_amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data transaksi lunas pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection