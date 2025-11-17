@extends('layouts.mechanic') {{-- PENTING: Gunakan layout mekanik --}}

@section('title', 'Detail Pekerjaan TRX-' . $transaction->id)

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- Kolom Kiri: Info Utama & Update Status --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pekerjaan</h6>
                </div>
                <div class="card-body">
                    <p><strong>Pelanggan:</strong> {{ $transaction->customer->name }}</p>
                    <p><strong>Kendaraan:</strong> {{ $transaction->vehicle->plate_number }} ({{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }})</p>
                    <p><strong>Status Saat Ini:</strong> <span class="badge badge-info">{{ $transaction->serviceStatus->label }}</span></p>
                    <hr>
                    <p><strong>Keluhan Awal:</strong></p>
                    <p>{{ $transaction->notes ?? 'Tidak ada catatan.' }}</p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Status Pekerjaan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('mechanic.jobs.updateStatus', $transaction->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="service_status_id">Ubah Status Menjadi:</label>
                            <select name="service_status_id" id="service_status_id" class="form-control" required>
                                <option value="">-- Pilih Status Baru --</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Jasa & Sparepart --}}
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rincian Pekerjaan</h6>
                </div>
                <div class="card-body">
                    <h5>Jasa yang Dikerjakan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Jasa</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaction->transactionServices as $detail)
                                    <tr>
                                        <td>{{ $detail->service->name }}</td>
                                        <td>Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center">Belum ada jasa.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mt-4">Sparepart yang Digunakan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                             <thead>
                                <tr>
                                    <th>Nama Sparepart</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaction->transactionSpareParts as $detail)
                                    <tr>
                                        <td>
                                            {{ $detail->sparePart->name }}
                                            @if($detail->serial_number)
                                                <br><small class="text-muted">No. Seri: {{ $detail->serial_number }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $detail->qty }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center">Belum ada sparepart.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection