@extends('layouts.mechanic')

@section('title', 'Detail Pengerjaan')

@section('content')
<div class="container-fluid">

    <a href="{{ route('mechanic.dashboard') }}" class="btn btn-secondary btn-sm mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>

    <div class="row">
        <!-- Detail Kendaraan & Keluhan -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Info Kendaraan & Customer</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Customer</th>
                            <td>: {{ $transaction->customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Kendaraan</th>
                            <td>: {{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }}</td>
                        </tr>
                        <tr>
                            <th>Plat Nomor</th>
                            <td>: <span class="badge badge-dark">{{ $transaction->vehicle->plate_number }}</span></td>
                        </tr>
                        <tr>
                            <th>Keluhan</th>
                            <td>: {{ $transaction->notes }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Update Status Form -->
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Update Status Pengerjaan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('mechanic.jobs.updateStatus', $transaction->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Status Sekarang:</label>
                            <select name="service_status_id" class="form-control">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ $transaction->service_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                            UPDATE STATUS
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Detail Jasa & Sparepart (Read Only buat Mekanik) -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Perbaikan & Sparepart</h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">Jasa Service:</h6>
                    <ul class="list-group mb-3">
                        @forelse($transaction->services as $service)
                            <li class="list-group-item">{{ $service->service->name }}</li>
                        @empty
                            <li class="list-group-item text-muted">Belum ada jasa diinput Admin.</li>
                        @endforelse
                    </ul>

                    <h6 class="font-weight-bold">Sparepart:</h6>
                    <ul class="list-group">
                        @forelse($transaction->spareParts as $part)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $part->sparePart->name }}
                                <span class="badge badge-primary badge-pill">{{ $part->qty }} pcs</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Belum ada sparepart diinput Admin.</li>
                        @endforelse
                    </ul>
                    
                    <div class="alert alert-info mt-3 small">
                        <i class="fas fa-info-circle"></i> Jika ada penambahan jasa/sparepart, silakan lapor ke Admin/Kasir untuk diinput ke sistem.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection