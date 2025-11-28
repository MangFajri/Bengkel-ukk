@extends('layouts.mechanic')

@section('title', 'Dashboard Mekanik')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tugas Saya (Active Jobs)</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tabel Daftar Tugas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Antrian Perbaikan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tgl Masuk</th>
                            <th>Kendaraan</th>
                            <th>Keluhan</th>
                            <th>Status Saat Ini</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                        <tr>
                            <td>{{ $job->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <strong>{{ $job->vehicle->brand }} {{ $job->vehicle->model }}</strong><br>
                                <span class="text-muted small">{{ $job->vehicle->plate_number }}</span>
                            </td>
                            <td>{{ Str::limit($job->notes, 50) }}</td>
                            <td>
                                <span class="badge badge-info">{{ $job->serviceStatus->label ?? 'Pending' }}</span>
                            </td>
                            <td>
                                <a href="{{ route('mechanic.jobs.show', $job->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-tools"></i> Kerjakan
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">
                                <i class="fas fa-mug-hot fa-2x mb-2"></i><br>
                                Belum ada tugas masuk. Santai dulu, ngopi dulu! ☕
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