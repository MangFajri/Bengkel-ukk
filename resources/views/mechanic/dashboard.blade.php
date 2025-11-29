@extends('layouts.mechanic')

@section('title', 'Dashboard Mekanik')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white font-weight-bold">Dashboard & Statistik</h1>
    </div>

    <div class="row">

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tugas Aktif (Sekarang)</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['total_active'] }} Mobil</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wrench fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Masuk Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['today'] }} Mobil</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Diselesaikan</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['finished'] }} Mobil</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
            <h6 class="m-0 font-weight-bold text-white">Pekerjaan Terbaru (Ringkasan)</h6>
            <a href="{{ route('mechanic.jobs.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Kendaraan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentJobs as $job)
                        <tr>
                            <td>
                                <strong>{{ $job->vehicle->plate_number }}</strong><br>
                                <small class="text-gray-400">{{ $job->vehicle->brand }}</small>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $job->serviceStatus->label }}</span>
                            </td>
                            <td>
                                <a href="{{ route('mechanic.jobs.show', $job->id) }}" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada tugas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection