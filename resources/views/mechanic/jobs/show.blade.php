@extends('layouts.mechanic')

@section('title', 'Detail Pekerjaan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-white font-weight-bold font-display text-uppercase">Detail Job #{{ $transaction->id }}</h1>
    <a href="{{ route('mechanic.dashboard') }}" class="btn btn-sm btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<div class="row">
    
    <!-- Kolom Kiri: Info Mobil & Keluhan -->
    <div class="col-lg-8">
        <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-body text-white">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="small text-gray-400 font-weight-bold text-uppercase">Kendaraan</label>
                        <div class="h5 font-weight-bold text-white">{{ $transaction->vehicle->plate_number }}</div>
                        <div class="text-primary">{{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }}</div>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <label class="small text-gray-400 font-weight-bold text-uppercase">Customer</label>
                        <div class="font-weight-bold">{{ $transaction->customer->name }}</div>
                        <div class="small text-gray-400">{{ $transaction->customer->phone ?? '-' }}</div>
                    </div>
                </div>

                <div class="p-3 rounded mb-4" style="background-color: #0f172a; border-left: 4px solid #f97316;">
                    <label class="small text-gray-400 font-weight-bold text-uppercase">Keluhan / Catatan</label>
                    <p class="mb-0">{{ $transaction->notes ?? 'Tidak ada catatan.' }}</p>
                </div>

                <h6 class="font-weight-bold text-primary mt-4 mb-3">Layanan yang Dikerjakan:</h6>
                <ul class="list-group list-group-flush">
                    @foreach($transaction->services as $service)
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; color: white; border-color: #334155;">
                        <span><i class="fas fa-check text-success mr-2"></i> {{ $service->service->name }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Panel Aksi -->
    <div class="col-lg-4">
        <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                <h6 class="m-0 font-weight-bold text-primary text-center">STATUS PENGERJAAN</h6>
            </div>
            <div class="card-body text-center">
                
                <div class="mb-4">
                    <span class="display-4 font-weight-bold text-white">
                        @if($transaction->service_status_id == 2)
                            Waiting
                        @elseif($transaction->service_status_id == 3)
                            Running
                        @endif
                    </span>
                    <div class="small text-gray-400 mt-1">Status Saat Ini</div>
                </div>

                @if($transaction->service_status_id == 2) <!-- Status Waiting -->
                    <form action="{{ route('mechanic.jobs.updateStatus', $transaction->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status_code" value="in_progress">
                        <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold text-uppercase shadow-lg">
                            <i class="fas fa-play mr-2"></i> Mulai Kerjakan
                        </button>
                    </form>
                
                @elseif($transaction->service_status_id == 3) <!-- Status In Progress -->
                    <div class="alert alert-info mb-4" role="alert">
                        <i class="fas fa-info-circle mr-1"></i> Jangan lupa cek ulang sebelum selesai.
                    </div>
                    <form action="{{ route('mechanic.jobs.updateStatus', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin pekerjaan ini sudah selesai?');">
                        @csrf
                        <input type="hidden" name="status_code" value="done">
                        <button type="submit" class="btn btn-success btn-block py-3 font-weight-bold text-uppercase shadow-lg">
                            <i class="fas fa-flag-checkered mr-2"></i> Selesaikan Pekerjaan
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection