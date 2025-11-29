@extends('layouts.admin')

@section('title', 'Kelola Transaksi #' . $transaction->id)

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Transaksi #{{ $transaction->id }}</h1>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Utama</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="small text-gray-600 font-weight-bold">Customer</label>
                                <input type="text" class="form-control" value="{{ $transaction->customer->name ?? 'User Terhapus' }}" readonly disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-gray-600 font-weight-bold">Kendaraan</label>
                                <input type="text" class="form-control"
                                    value="{{ $transaction->vehicle->brand ?? '' }} {{ $transaction->vehicle->model ?? '' }} ({{ $transaction->vehicle->plate_number ?? '-' }})"
                                    readonly disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small text-gray-600 font-weight-bold">Keluhan Customer</label>
                            <textarea class="form-control" rows="2" readonly disabled>{{ $transaction->notes ?? '-' }}</textarea>
                        </div>

                        <hr>
                        
                        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-cogs mr-1"></i> Update Status Pengerjaan</h6>
                        
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Pilih Mekanik</label>
                                <select name="mechanic_id" class="form-control">
                                    <option value="">-- Belum Ditunjuk --</option>
                                    @foreach($mechanics as $mechanic)
                                        <option value="{{ $mechanic->id }}" {{ $transaction->mechanic_id == $mechanic->id ? 'selected' : '' }}>
                                            {{ $mechanic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Status Service</label>
                                <select name="service_status_id" class="form-control">
                                    @foreach($serviceStatuses as $status)
                                        <option value="{{ $status->id }}" {{ $transaction->service_status_id == $status->id ? 'selected' : '' }}>
                                            {{ $status->label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status Pembayaran</label>
                            <select name="payment_status_id" class="form-control">
                                @foreach($paymentStatuses as $status)
                                    <option value="{{ $status->id }}" {{ $transaction->payment_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan Status
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Item (Jasa & Sparepart)</h6>
                </div>
                <div class="card-body">
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="font-weight-bold">A. Jasa Service</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addServiceModal">
                            <i class="fas fa-plus"></i> Tambah Jasa
                        </button>
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Jasa</th>
                                    <th class="text-right">Biaya</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- PERBAIKAN: Menggunakan $ts sebagai Service Model, akses harga lewat Pivot --}}
                                @forelse($transaction->services as $ts)
                                <tr>
                                    {{-- Karena ini ManyToMany, $ts adalah Service. Panggil langsung namanya --}}
                                    <td>{{ $ts->name }}</td>
                                    
                                    {{-- Harga ada di tabel pivot --}}
                                    <td class="text-right">Rp {{ number_format($ts->pivot->price_at_time, 0, ',', '.') }}</td>
                                    
                                    <td class="text-center">
                                        {{-- Hapus berdasarkan ID di tabel Pivot (transaction_services) --}}
                                        <form action="{{ route('admin.transaction-services.destroy', $ts->pivot->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus jasa ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada jasa service ditambahkan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="font-weight-bold">B. Sparepart Digunakan</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addSparePartModal">
                            <i class="fas fa-plus"></i> Tambah Sparepart
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Harga Satuan</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Spareparts menggunakan HasMany, jadi cara aksesnya berbeda dengan Services --}}
                                @forelse($transaction->spareParts as $tsp)
                                <tr>
                                    <td>{{ $tsp->sparePart->name ?? 'Item Terhapus' }}</td>
                                    <td class="text-center">{{ $tsp->qty }}</td>
                                    <td class="text-right">Rp {{ number_format($tsp->price_at_time, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp {{ number_format($tsp->price_at_time * $tsp->qty, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.transaction-spareparts.destroy', $tsp->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus sparepart ini? Stok akan dikembalikan.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada sparepart ditambahkan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-receipt mr-1"></i> Total Tagihan</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Jasa:</span>
                        {{-- PERBAIKAN: Hitung sum dari pivot --}}
                        <span class="font-weight-bold">Rp {{ number_format($transaction->services->sum(function($s){ return $s->pivot->price_at_time; }), 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Sparepart:</span>
                        <span class="font-weight-bold">Rp {{ number_format($transaction->spareParts->sum(function($item){ return $item->price_at_time * $item->qty; }), 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="font-weight-bold">GRAND TOTAL:</h5>
                        <h4 class="font-weight-bold text-success">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h4>
                    </div>

                    @if($transaction->payment_status_id == 1) <div class="mt-4">
                            {{-- Pastikan route print sudah dibuat --}}
                            <a href="#" class="btn btn-dark btn-block" onclick="alert('Fitur Print belum dihubungkan ke Route')">
                                <i class="fas fa-print mr-1"></i> Cetak Struk
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning mt-3 text-center text-xs">
                            Transaksi belum lunas.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Jasa Service</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.transaction-services.store') }}" method="POST">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Jasa</label>
                        <select name="service_id" class="form-control" required>
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->name }} (Rp {{ number_format($s->price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addSparePartModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Tambah Sparepart</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.transaction-spareparts.store') }}" method="POST">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Sparepart</label>
                        <select name="spare_part_id" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($spareParts as $sp)
                                <option value="{{ $sp->id }}">
                                    {{-- Sudah diperbaiki menggunakan sell_price sesuai request --}}
                                    {{ $sp->name }} 
                                    - Stok: {{ $sp->stock }} 
                                    (Rp {{ number_format($sp->sell_price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah (Qty)</label>
                        <input type="number" name="qty" class="form-control" min="1" value="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection