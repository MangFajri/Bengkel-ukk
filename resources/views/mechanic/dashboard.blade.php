@extends('layouts.mechanic')

@section('title', 'Dashboard Mekanik')

@section('content')
    <!-- Header Stat -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-primary shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155; border-left: 5px solid #f97316 !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Selesai Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $jobsToday }} Mobil</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Antrian Pekerjaan -->
    <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
            <h6 class="m-0 font-weight-bold text-white text-uppercase"><i class="fas fa-tools mr-2 text-primary"></i>Antrian Pekerjaan Saya</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-striped mb-0" style="background-color: #0f172a;">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-xs font-weight-bolder text-white-50">Mobil</th>
                            <th class="text-uppercase text-xs font-weight-bolder text-white-50">Keluhan</th>
                            <th class="text-uppercase text-xs font-weight-bolder text-white-50">Status</th>
                            <th class="text-center text-uppercase text-xs font-weight-bolder text-white-50">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeJobs as $job)
                        <tr>
                            <td class="align-middle">
                                <div class="font-weight-bold text-white">{{ $job->vehicle->plate_number }}</div>
                                <div class="small text-gray-400">{{ $job->vehicle->brand }} {{ $job->vehicle->model }}</div>
                            </td>
                            <td class="align-middle text-sm">
                                {{ Str::limit($job->notes, 50) ?? '-' }}
                            </td>
                            <td class="align-middle">
                                @if($job->service_status_id == 3)
                                    <span class="badge badge-info px-3 py-2">Sedang Dikerjakan</span>
                                @else
                                    <span class="badge badge-warning px-3 py-2 text-dark">Menunggu</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ route('mechanic.jobs.show', $job->id) }}" class="btn btn-primary btn-sm font-weight-bold">
                                    <i class="fas fa-arrow-right mr-1"></i> KERJAKAN
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-gray-500 mb-2"><i class="fas fa-mug-hot fa-3x"></i></div>
                                <h6 class="font-weight-bold">Lagi santai nih!</h6>
                                <p class="small">Belum ada pekerjaan yang ditugaskan admin.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection