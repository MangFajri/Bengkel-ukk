@extends('layouts.mechanic')

@section('title', 'Working Space #' . $transaction->id)

@section('content')
<div class="container-fluid">

    <!-- Header & Tombol Kembali -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-100 font-weight-bold">
            🛠️ Pengerjaan: <span class="text-warning">{{ $transaction->vehicle->plate_number }}</span>
        </h1>
        <a href="{{ route('mechanic.jobs.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <!-- Alert Status -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row">

        <!-- Kolom Kiri: Info & Status -->
        <div class="col-lg-4">
            
            <!-- Card Update Status -->
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-primary">Status Pengerjaan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('mechanic.jobs.update-status', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label class="font-weight-bold text-gray-400">Update Status:</label>
                            
                            {{-- FIX UTAMA: $status->label (BUKAN NAME) --}}
                            {{-- Style: Paksa background gelap & teks putih untuk dropdown --}}
                            <select name="service_status_id" class="form-control font-weight-bold" 
                                    style="background-color: #0f172a; color: #ffffff; border: 1px solid #475569;">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" style="background-color: #1e293b; color: #ffffff;"
                                            {{ $transaction->service_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->label }} {{-- <--- INI PERBAIKANNYA --}}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card Info Kendaraan -->
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-info">Info Kendaraan</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm text-gray-300">
                        <tr>
                            <td class="text-gray-500" width="35%">Pelanggan</td>
                            <td class="font-weight-bold text-white">{{ $transaction->customer->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-gray-500">Kendaraan</td>
                            <td class="font-weight-bold text-white">{{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }}</td>
                        </tr>
                        <tr>
                            <td class="text-gray-500">Plat Nomor</td>
                            <td>
                                <span class="badge badge-secondary px-2 py-1" style="font-size: 0.9rem; border: 1px solid #475569;">
                                    {{ $transaction->vehicle->plate_number }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-gray-500">Keluhan</td>
                            <td>
                                <div class="text-danger font-weight-bold bg-gray-900 p-2 rounded border border-danger small">
                                    {{ $transaction->notes ?? '-' }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Jasa & Sparepart -->
        <div class="col-lg-8">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-primary">Rincian Biaya & Item</h6>
                    <h6 class="m-0 font-weight-bold text-success border border-success px-3 py-1 rounded bg-gray-900">
                        Total: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </h6>
                </div>
                <div class="card-body">
                    
                    <!-- 1. List Jasa -->
                    <h6 class="font-weight-bold text-gray-300 border-bottom border-gray-700 pb-2 mb-3">
                        <i class="fas fa-wrench mr-1 text-info"></i> Jasa Service
                    </h6>
                    <ul class="list-group mb-4">
                        @forelse($transaction->services as $service)
                            <li class="list-group-item d-flex justify-content-between align-items-center" 
                                style="background-color: #0f172a; border: 1px solid #334155; color: #e2e8f0;">
                                <span class="font-weight-bold">{{ $service->name }}</span>
                                <span class="badge badge-secondary border border-gray-600">
                                    Rp {{ number_format($service->pivot->price_at_time, 0, ',', '.') }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-gray-500 font-italic text-center" style="background-color: transparent; border: 1px dashed #475569;">
                                Tidak ada jasa dipilih
                            </li>
                        @endforelse
                    </ul>

                    <!-- 2. List Sparepart -->
                    <div class="d-flex justify-content-between align-items-center mb-2 mt-4 border-bottom border-gray-700 pb-2">
                        <h6 class="font-weight-bold text-gray-300">
                            <i class="fas fa-box-open mr-1 text-warning"></i> Sparepart Digunakan
                        </h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-gray-300" style="border-color: #475569;">
                            <thead style="background-color: #0f172a;">
                                <tr>
                                    <th style="border-color: #475569;">Nama Barang</th>
                                    <th width="10%" class="text-center" style="border-color: #475569;">Qty</th>
                                    <th width="25%" class="text-right" style="border-color: #475569;">Harga</th>
                                    <th width="10%" class="text-center" style="border-color: #475569;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaction->spareParts as $part)
                                <tr style="background-color: #1e293b;">
                                    <td class="align-middle text-white font-weight-bold" style="border-color: #475569;">{{ $part->name }}</td>
                                    <td class="text-center align-middle font-weight-bold text-white" style="border-color: #475569;">{{ $part->pivot->qty }}</td>
                                    <td class="text-right align-middle text-white" style="border-color: #475569;">Rp {{ number_format($part->pivot->price_at_time, 0, ',', '.') }}</td>
                                    <td class="text-center align-middle" style="border-color: #475569;">
                                        @if($transaction->payment_status_id != 1)
                                            <form action="{{ route('mechanic.spare-parts.destroy', $part->pivot->id) }}" method="POST" onsubmit="return confirm('Hapus item ini? Stok akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-circle shadow-sm" title="Hapus Item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <i class="fas fa-lock text-gray-500" title="Terkunci"></i>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-gray-500 font-italic py-3" style="background-color: transparent; border: 1px dashed #475569;">
                                        Belum ada sparepart tambahan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- 3. Form Tambah Sparepart -->
                    @if($transaction->payment_status_id != 1)
                        <div class="card mt-4 shadow-sm border-left-success" style="background-color: #0f172a; border: 1px solid #334155; border-left: 4px solid #1cc88a !important;">
                            <div class="card-body py-3">
                                <h6 class="font-weight-bold mb-3 text-success">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Sparepart Baru
                                </h6>
                                <form action="{{ route('mechanic.spare-parts.store') }}" method="POST" class="form-row align-items-end">
                                    @csrf
                                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                                    
                                    <div class="col-md-7 mb-2">
                                        <label class="sr-only">Pilih Barang</label>
                                        {{-- SELECT BARANG JUGA FIX LABEL --}}
                                        <select name="spare_part_id" class="form-control custom-select font-weight-bold" 
                                                style="background-color: #334155; color: #ffffff; border: 1px solid #4e73df;" required>
                                            <option value="" style="background-color: #1e293b; color: #94a3b8;">-- Cari Barang --</option>
                                            @foreach($availableSpareParts as $part)
                                                <option value="{{ $part->id }}" style="background-color: #1e293b; color: #ffffff;">
                                                    {{-- Untuk sparepart kolomnya 'name', jadi ini sudah benar --}}
                                                    {{ $part->name }} (Sisa: {{ $part->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <label class="sr-only">Qty</label>
                                        <input type="number" name="qty" class="form-control font-weight-bold" 
                                               style="background-color: #334155; color: #ffffff; border: 1px solid #4e73df;" 
                                               placeholder="Jml" value="1" min="1" required>
                                    </div>

                                    <div class="col-md-2 mb-2">
                                        <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert mt-3 shadow-sm text-center" style="background-color: #1e293b; border: 1px solid #36b9cc; color: #36b9cc;">
                            <i class="fas fa-info-circle mr-1"></i> 
                            <strong>Info:</strong> Transaksi sudah LUNAS. Tidak bisa ubah data.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@endsection