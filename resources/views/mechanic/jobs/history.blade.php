@extends('layouts.mechanic')

@section('title', 'Riwayat Pekerjaan')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white font-weight-bold font-display text-uppercase">Riwayat Service Selesai</h1>
        <a href="{{ route('mechanic.dashboard') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3 border-bottom border-secondary" style="background-color: #1e293b;">
            <h6 class="m-0 font-weight-bold text-success text-uppercase"><i class="fas fa-check-double mr-2"></i> Pekerjaan Selesai</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped mb-0" id="dataTable" width="100%" cellspacing="0" style="background-color: #0f172a;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Selesai</th>
                            <th>Kendaraan</th>
                            <th>Customer</th>
                            <th>Layanan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyJobs as $job)
                        <tr>
                            <td class="text-gray-400">#{{ $job->id }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($job->check_out_at)->format('d M Y, H:i') }}
                            </td>
                            <td>
                                <div class="font-weight-bold text-white">{{ $job->vehicle->plate_number }}</div>
                                <div class="small text-gray-400">{{ $job->vehicle->brand }} {{ $job->vehicle->model }}</div>
                            </td>
                            <td>{{ $job->customer->name }}</td>
                            <td>
                                <ul class="pl-3 mb-0 small text-gray-300">
                                    @foreach($job->services as $svc)
                                        <li>{{ $svc->service->name ?? 'Service' }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success px-2 py-1">Selesai</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-gray-500">
                                <i class="fas fa-history fa-3x mb-3 opacity-50"></i>
                                <p>Belum ada riwayat pekerjaan selesai.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Links -->
            <div class="mt-4">
                {{ $historyJobs->links() }}
            </div>
        </div>
    </div>
@endsection