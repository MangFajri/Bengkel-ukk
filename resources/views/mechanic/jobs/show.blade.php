@extends('layouts.mechanic')

@section('title', 'Detail Pengerjaan #' . $transaction->id)

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-white font-weight-bold">Area Kerja Mekanik</h1>
            <a href="{{ route('mechanic.jobs.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-2"></i>Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                    <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Kendaraan & Keluhan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="text-gray-400 small text-uppercase font-weight-bold">Kendaraan</label>
                                {{-- PENGAMAN 1: Gunakan ?? untuk Vehicle --}}
                                <h5 class="text-white font-weight-bold">
                                    {{ $transaction->vehicle->brand ?? 'Mobil' }}
                                    {{ $transaction->vehicle->model ?? 'Umum' }}
                                </h5>
                                <div class="badge badge-warning text-dark">
                                    {{ $transaction->vehicle->plate_number ?? 'Tanpa Plat' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-gray-400 small text-uppercase font-weight-bold">Pemilik</label>
                                {{-- PENGAMAN 2: Gunakan ?? untuk Customer --}}
                                <div class="text-white font-weight-bold">
                                    <i class="fas fa-user mr-2"></i>{{ $transaction->customer->name ?? 'User Terhapus' }}
                                </div>
                                <div class="text-gray-400 small">
                                    <i class="fas fa-phone mr-2"></i>{{ $transaction->customer->phone ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded mb-3" style="background-color: #0f172a; border-left: 4px solid #f6c23e;">
                            <label class="text-warning small font-weight-bold mb-1">KELUHAN / CATATAN:</label>
                            <p class="text-white mb-0 font-italic">
                                "{{ $transaction->notes ?? 'Tidak ada catatan khusus.' }}"</p>
                        </div>

                        <hr class="border-secondary">

                        <h6 class="font-weight-bold text-primary mb-3">Daftar Pekerjaan (Service List)</h6>
                        <ul class="list-group list-group-flush" style="background-color: transparent;">
                            @forelse($transaction->services as $service)
                                <li class="list-group-item d-flex justify-content-between align-items-center text-white"
                                    style="background-color: transparent; border-color: #334155;">
                                    <div>
                                        <i class="fas fa-wrench text-info mr-2"></i>
                                        {{-- PENGAMAN 3: Service Name --}}
                                        {{ $service->name ?? 'Service Item' }}
                                    </div>
                                    <span class="badge badge-secondary">Jasa</span>
                                </li>
                            @empty
                                <li class="list-group-item text-muted bg-transparent border-0">Tidak ada jasa khusus (Cek
                                    keluhan).</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                    <div class="card-header py-3 bg-success text-white">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-tasks mr-2"></i> Update Status</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mechanic.jobs.updateStatus', $transaction->id) }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label class="text-gray-400 small font-weight-bold">Status Pengerjaan Saat Ini</label>
                                <select name="service_status_id" class="form-control text-white"
                                    style="background-color: #0f172a; border: 1px solid #334155;">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ $transaction->service_status_id == $status->id ? 'selected' : '' }}>
                                            {{ $status->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2">
                                <i class="fas fa-save mr-2"></i> Simpan Status
                            </button>
                        </form>

                        <div class="mt-4 text-center">
                            <span class="text-gray-500 small d-block mb-2">Instruksi:</span>
                            <div class="alert alert-info py-2 small mb-0">
                                Ubah status menjadi <strong>"Selesai"</strong> jika mobil sudah siap diambil oleh customer.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                    <div class="card-header py-3" style="background-color: #1e293b;">
                        <h6 class="m-0 font-weight-bold text-gray-400 text-xs text-uppercase">Sparepart Digunakan</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-dark table-sm mb-0">
                            <thead>
                                <tr>
                                    <th class="pl-3">Barang</th>
                                    <th class="text-center">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaction->spareParts as $part)
                                    <tr>
                                        <td class="pl-3 small">{{ $part->sparePart->name ?? 'Item Terhapus' }}</td>
                                        <td class="text-center small">{{ $part->qty }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center small text-muted py-3">Tidak ada sparepart.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="p-3 bg-secondary mt-3 rounded">
                            <h6 class="text-white mb-2">Tambah Sparepart</h6>
                            <form action="{{ route('mechanic.jobs.spareparts.store', $transaction->id) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <select name="spare_part_id" class="form-control custom-select" required>
                                        <option value="">Pilih Barang...</option>
                                        @foreach(\App\Models\SparePart::where('stock', '>', 0)->get() as $part)
                                            <option value="{{ $part->id }}">
                                                {{ $part->name }} (Stok: {{ $part->stock }}) - Rp
                                                {{ number_format($part->price) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="qty" class="form-control" placeholder="Qty" value="1" min="1"
                                        style="max-width: 80px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="submit">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection