@extends('layouts.admin')

@section('title', 'Kelola Transaksi #' . $transaction->id)

@section('content')
    <div class="row">

        <!-- Kolom Kiri: Detail & Aksi -->
        <div class="col-lg-8">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                    style="background-color: #1e293b;">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Service</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="small text-gray-400 font-weight-bold">Customer</label>
                                <input type="text" class="form-control" value="{{ $transaction->customer->name }}" readonly
                                    disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-gray-400 font-weight-bold">Kendaraan</label>
                                <input type="text" class="form-control"
                                    value="{{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }} ({{ $transaction->vehicle->plate_number }})"
                                    readonly disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small text-gray-400 font-weight-bold">Keluhan Customer</label>
                            <textarea class="form-control" rows="2" readonly disabled
                                style="background-color: #0f172a;">{{ $transaction->notes ?? '-' }}</textarea>
                        </div>

                        <hr class="border-secondary my-4">

                        <!-- BAGIAN UPDATE STATUS (PENTING) -->
                        <h6 class="text-warning font-weight-bold mb-3"><i class="fas fa-cogs mr-1"></i> Update Pengerjaan
                        </h6>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="small text-gray-400 font-weight-bold">Pilih Mekanik</label>
                                <select name="mechanic_id" class="form-control"
                                    style="background-color: #0f172a; color: white;">
                                    <option value="">-- Belum Ditunjuk --</option>
                                    @foreach($mechanics as $mechanic)
                                        <option value="{{ $mechanic->id }}" {{ $transaction->mechanic_id == $mechanic->id ? 'selected' : '' }}>
                                            {{ $mechanic->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih mekanik agar job ini muncul di dashboard mereka.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="small text-gray-400 font-weight-bold">Status Service</label>
                                <select name="service_status_id" class="form-control"
                                    style="background-color: #0f172a; color: white;">
                                    @foreach($serviceStatuses as $status)
                                        <option value="{{ $status->id }}" {{ $transaction->service_status_id == $status->id ? 'selected' : '' }}>
                                            {{ $status->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small text-gray-400 font-weight-bold">Status Pembayaran</label>
                            <select name="payment_status_id" class="form-control"
                                style="background-color: #0f172a; color: white;">
                                @foreach($paymentStatuses as $status)
                                    <option value="{{ $status->id }}" {{ $transaction->payment_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 mt-4">
                            <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Biaya -->
        <!-- Kolom Kanan: Rincian Biaya -->
        <div class="col-lg-4">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                    style="background-color: #1e293b;">
                    <h6 class="m-0 font-weight-bold text-primary">Rincian Biaya</h6>

                    <!-- TOMBOL CETAK (Hanya muncul jika status sudah LUNAS / PAID) -->
                    @if($transaction->payment_status_id == 1)
                        <a href="{{ route('admin.transactions.print', $transaction->id) }}" target="_blank"
                            class="btn btn-sm btn-success shadow-sm">
                            <i class="fas fa-print fa-sm text-white-50 mr-1"></i> Cetak
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3" style="background-color: transparent;">
                        @foreach($transaction->services as $service)
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                style="background-color: #0f172a; border-color: #334155; color: white;">
                                {{ $service->service->name ?? 'Layanan' }}
                                <span class="badge badge-warning badge-pill">Rp
                                    {{ number_format($service->price_at_time, 0, ',', '.') }}</span>
                            </li>
                        @endforeach

                        <!-- Disini nanti list sparepart -->
                    </ul>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h5 class="font-weight-bold text-white">TOTAL</h5>
                        <h4 class="font-weight-bold text-warning">Rp
                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection