@extends('layouts.admin')

@section('title', 'Edit Transaksi #' . $transaction->id)

@section('content')
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-100 font-weight-bold">Edit Transaksi: <span class="text-warning">#{{ $transaction->id }}</span></h1>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <!-- Alert -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row">

        <!-- KOLOM KIRI: Data Utama (Customer, Mobil, Status) -->
        <div class="col-lg-4">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-primary">Data Utama</h6>
                </div>
                <div class="card-body">
                    
                    <!-- Info Customer (Read Only) -->
                    <div class="form-group">
                        <label class="text-gray-400 font-weight-bold">Pelanggan</label>
                        <input type="text" class="form-control text-white font-weight-bold" style="background-color: #334155; border: 1px solid #475569;" 
                               value="{{ $transaction->customer->name ?? 'Umum' }}" readonly>
                    </div>

                    <!-- Info Kendaraan (Read Only) -->
                    <div class="form-group">
                        <label class="text-gray-400 font-weight-bold">Kendaraan</label>
                        <input type="text" class="form-control text-white font-weight-bold" style="background-color: #334155; border: 1px solid #475569;" 
                               value="{{ $transaction->vehicle->plate_number ?? '-' }} ({{ $transaction->vehicle->model ?? '' }})" readonly>
                    </div>

                    <!-- Keluhan (Read Only) -->
                    <div class="form-group">
                        <label class="text-gray-400 font-weight-bold">Keluhan / Notes</label>
                        <textarea class="form-control text-white font-weight-bold" rows="2" style="background-color: #334155; border: 1px solid #475569;" readonly>{{ $transaction->notes }}</textarea>
                    </div>
                    
                    <hr style="border-color: #475569;">

                    <!-- FORM UPDATE STATUS (UTAMA) -->
                    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- 1. Pilih Mekanik (FIX DROPDOWN) -->
                        <div class="form-group">
                            <label class="text-gray-100 font-weight-bold">Pilih Mekanik</label>
                            <select name="mechanic_id" class="form-control font-weight-bold" 
                                    style="background-color: #0f172a; color: #ffffff; border: 1px solid #4e73df;">
                                <option value="" style="background-color: #1e293b; color: #ccc;">-- Belum Ada --</option>
                                @foreach($mechanics as $mechanic)
                                    <option value="{{ $mechanic->id }}" style="background-color: #1e293b; color: #ffffff;"
                                        {{ $transaction->mechanic_id == $mechanic->id ? 'selected' : '' }}>
                                        {{ $mechanic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 2. Status Service (FIX DROPDOWN & LABEL) -->
                        <div class="form-group">
                            <label class="text-gray-100 font-weight-bold">Status Service</label>
                            <select name="service_status_id" class="form-control font-weight-bold"
                                    style="background-color: #0f172a; color: #ffffff; border: 1px solid #4e73df;">
                                @foreach($serviceStatuses as $status)
                                    <option value="{{ $status->id }}" style="background-color: #1e293b; color: #ffffff;"
                                        {{ $transaction->service_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->label }} {{-- <--- FIX: Pakai LABEL, bukan NAME --}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. Status Pembayaran (FIX DROPDOWN & LABEL) -->
                        <div class="form-group">
                            <label class="text-gray-100 font-weight-bold">Status Pembayaran</label>
                            <select name="payment_status_id" class="form-control font-weight-bold"
                                    style="background-color: #0f172a; color: #ffffff; border: 1px solid #4e73df;">
                                @foreach($paymentStatuses as $status)
                                    <option value="{{ $status->id }}" style="background-color: #1e293b; color: #ffffff;"
                                        {{ $transaction->payment_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->label }} {{-- <--- FIX: Pakai LABEL, bukan NAME --}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold mt-4 shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan Utama
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Detail Item (Services & Spareparts) -->
        <div class="col-lg-8">
            
            <!-- 1. DATA JASA (SERVICES) -->
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-tools mr-1"></i> Jasa Service</h6>
                </div>
                <div class="card-body">
                    
                    {{-- Tabel List Jasa --}}
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm text-gray-300" style="border-color: #475569;">
                            <thead style="background-color: #0f172a;">
                                <tr>
                                    <th style="border-color: #475569;">Nama Jasa</th>
                                    <th width="25%" class="text-right" style="border-color: #475569;">Biaya</th>
                                    <th width="10%" class="text-center" style="border-color: #475569;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaction->services as $service)
                                    <tr style="background-color: #1e293b;">
                                        <td class="align-middle text-white font-weight-bold" style="border-color: #475569;">{{ $service->name }}</td>
                                        <td class="align-middle text-right text-white font-weight-bold" style="border-color: #475569;">Rp {{ number_format($service->pivot->price_at_time, 0, ',', '.') }}</td>
                                        <td class="align-middle text-center" style="border-color: #475569;">
                                            @if($transaction->payment_status_id != 1)
                                                <form action="{{ route('admin.transaction-services.destroy', $service->pivot->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm btn-circle shadow-sm" onclick="return confirm('Hapus jasa ini?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @else
                                                <i class="fas fa-lock text-secondary"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted font-italic py-2" style="border-color: #475569; background-color: transparent;">Belum ada jasa</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Form Tambah Jasa (FIX DROPDOWN JASA) --}}
                    @if($transaction->payment_status_id != 1)
                        <form action="{{ route('admin.transaction-services.store') }}" method="POST" class="form-inline justify-content-end">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                            
                            <select name="service_id" class="form-control form-control-sm mr-2 text-white font-weight-bold custom-select" 
                                    style="background-color: #334155; border: 1px solid #36b9cc; width: 60%;">
                                <option value="" style="background-color: #1e293b; color: #ccc;">+ Tambah Jasa</option>
                                @foreach($services as $svc)
                                    <option value="{{ $svc->id }}" style="background-color: #1e293b; color: #fff;">
                                        {{ $svc->name }} - Rp {{ number_format($svc->price) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-info btn-sm font-weight-bold shadow-sm">Tambah</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- 2. DATA SPAREPART -->
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-box mr-1"></i> Sparepart / Barang</h6>
                </div>
                <div class="card-body">

                    {{-- Tabel List Sparepart --}}
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm text-gray-300" style="border-color: #475569;">
                            <thead style="background-color: #0f172a;">
                                <tr>
                                    <th style="border-color: #475569;">Nama Barang</th>
                                    <th width="10%" class="text-center" style="border-color: #475569;">Qty</th>
                                    <th width="25%" class="text-right" style="border-color: #475569;">Subtotal</th>
                                    <th width="10%" class="text-center" style="border-color: #475569;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaction->spareParts as $part)
                                    <tr style="background-color: #1e293b;">
                                        <td class="align-middle text-white font-weight-bold" style="border-color: #475569;">{{ $part->name }}</td>
                                        <td class="align-middle text-center text-white font-weight-bold" style="border-color: #475569;">{{ $part->pivot->qty }}</td>
                                        <td class="align-middle text-right text-white" style="border-color: #475569;">
                                            Rp {{ number_format($part->pivot->price_at_time * $part->pivot->qty, 0, ',', '.') }}
                                        </td>
                                        <td class="align-middle text-center" style="border-color: #475569;">
                                            @if($transaction->payment_status_id != 1)
                                                <form action="{{ route('admin.transaction-spareparts.destroy', $part->pivot->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm btn-circle shadow-sm" onclick="return confirm('Hapus barang ini? Stok akan kembali.')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @else
                                                <i class="fas fa-lock text-secondary"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted font-italic py-2" style="border-color: #475569; background-color: transparent;">Belum ada sparepart</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Form Tambah Sparepart (FIX DROPDOWN BARANG) --}}
                    @if($transaction->payment_status_id != 1)
                        <form action="{{ route('admin.transaction-spareparts.store') }}" method="POST" class="form-row align-items-end justify-content-end">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                            
                            <div class="col-7">
                                <select name="spare_part_id" class="form-control form-control-sm text-white font-weight-bold custom-select" 
                                        style="background-color: #334155; border: 1px solid #f6c23e;">
                                    <option value="" style="background-color: #1e293b; color: #ccc;">+ Tambah Barang</option>
                                    @foreach($spareParts as $part)
                                        <option value="{{ $part->id }}" style="background-color: #1e293b; color: #fff;">
                                            {{ $part->name }} (Stok: {{ $part->stock }}) - Rp {{ number_format($part->sell_price) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="qty" class="form-control form-control-sm text-white font-weight-bold text-center" 
                                       style="background-color: #334155; border: 1px solid #f6c23e;" placeholder="Qty" value="1" min="1">
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-warning btn-sm btn-block font-weight-bold text-dark shadow-sm">Add</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- 3. PANEL PEMBAYARAN -->
            <div class="card shadow mb-4 border-left-success" style="background-color: #1e293b; border: 1px solid #334155; border-left: 4px solid #1cc88a !important;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Tagihan</div>
                        <div class="h4 mb-0 font-weight-bold text-white">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                    </div>
                    
                    @if($transaction->payment_status_id != 1)
                        <button type="button" class="btn btn-success font-weight-bold shadow-sm" data-toggle="modal" data-target="#paymentModal">
                            <i class="fas fa-money-bill-wave mr-2"></i> Proses Pembayaran
                        </button>
                    @else
                        <div class="text-right">
                            <span class="badge badge-success px-3 py-2 mb-2 border border-success">LUNAS</span><br>
                            <a href="{{ route('admin.transactions.print', $transaction->id) }}" target="_blank" class="btn btn-sm btn-outline-light mt-1">
                                <i class="fas fa-print mr-1"></i> Cetak Struk
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Pembayaran (FIX DARK MODE MODAL) -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-white" style="background-color: #1e293b; border: 1px solid #475569;">
                <div class="modal-header" style="border-bottom: 1px solid #475569;">
                    <h5 class="modal-title font-weight-bold text-success">Proses Pembayaran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.transactions.updatePayment', $transaction->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="text-gray-300">Total Tagihan</label>
                            <input type="text" class="form-control-plaintext text-white font-weight-bold h4" 
                                   value="Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="text-gray-300">Metode Pembayaran</label>
                            {{-- FIX DROPDOWN MODAL --}}
                            <select name="payment_method_id" class="form-control font-weight-bold text-white" 
                                    style="background-color: #334155; border: 1px solid #475569;">
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" style="background-color: #1e293b; color: #fff;">
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="text-gray-300">Jumlah Bayar (Nominal)</label>
                            <input type="number" name="amount_paid" class="form-control text-white font-weight-bold" 
                                   style="background-color: #334155; border: 1px solid #475569;"
                                   value="{{ $transaction->total_amount }}" required>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #475569;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success font-weight-bold shadow-sm">Bayar & Lunas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection