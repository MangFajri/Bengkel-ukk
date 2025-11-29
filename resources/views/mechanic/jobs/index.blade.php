@extends('layouts.mechanic')

@section('title', 'Daftar Semua Pekerjaan')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white font-weight-bold">Daftar Semua Pekerjaan</h1>
    </div>

    <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
            <h6 class="m-0 font-weight-bold text-primary">Antrian Lengkap</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tgl Masuk</th>
                            <th>Kendaraan</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                        <tr>
                            <td>{{ $job->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <strong>{{ $job->vehicle->brand }} {{ $job->vehicle->model }}</strong><br>
                                <span class="badge badge-light text-dark">{{ $job->vehicle->plate_number }}</span>
                            </td>
                            <td>{{ Str::limit($job->notes, 40) }}</td>
                            <td>
                                <span class="badge badge-info">{{ $job->serviceStatus->label ?? 'Pending' }}</span>
                            </td>
                            <td>
                                <a href="{{ route('mechanic.jobs.show', $job->id) }}" class="btn btn-warning btn-sm font-weight-bold text-dark">
                                    <i class="fas fa-tools mr-1"></i> KERJAKAN
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada pekerjaan aktif.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>

</div>
@endsection