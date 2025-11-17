@extends('layouts.admin')

@section('title', 'Detail Transaksi TRX-' . $transaction->id)

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Kartu Info Transaksi --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Utama</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Pelanggan:</strong><p>{{ $transaction->customer->name }}</p>
                </div>
                <div class="col-md-4">
                    <strong>Kendaraan:</strong><p>{{ $transaction->vehicle->plate_number }} ({{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }})</p>
                </div>
                <div class="col-md-4">
                    <strong>Mekanik:</strong><p>{{ $transaction->mechanic->name }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Jasa --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Jasa</h6>
        </div>
        <div class="card-body">
            {{-- Form Tambah Jasa --}}
            <form action="{{ route('admin.transactions.services.store', $transaction->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <select name="service_id" class="form-control" required>
                            <option value="">-- Pilih Jasa --</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Tambah Jasa</button>
                    </div>
                </div>
            </form>

            {{-- Tabel Daftar Jasa yang Sudah Ditambahkan --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Jasa</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaction->transactionServices as $detail)
                        <tr>
                            <td>{{ $detail->service->name }}</td>
                            <td>Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('admin.transactions.services.destroy', $detail->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus item ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada jasa yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bagian Sparepart (Strukturnya sama dengan Jasa) --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Sparepart</h6>
        </div>
        <div class="card-body">
            {{-- Form Tambah Sparepart --}}
            <form action="{{ route('admin.transactions.spare-parts.store', $transaction->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <select name="spare_part_id" class="form-control" required>
                            <option value="">-- Pilih Sparepart --</option>
                            @foreach ($spareParts as $sparePart)
                                <option value="{{ $sparePart->id }}">{{ $sparePart->name }} (Stok: {{ $sparePart->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="qty" class="form-control" placeholder="Jumlah" value="1" min="1" required>
                    </div>
                     <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Tambah Sparepart</button>
                    </div>
                </div>
            </form>

            {{-- Tabel Daftar Sparepart yang Sudah Ditambahkan --}}
            <table class="table table-bordered">
                 <thead>
                    <tr>
                        <th>Nama Sparepart</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaction->transactionSpareParts as $detail)
                        <tr>
                            <td>{{ $detail->sparePart->name }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->price_at_time * $detail->qty, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('admin.transactions.spare-parts.destroy', $detail->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus item ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada sparepart yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tombol Aksi Final --}}
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>

    {{-- Area Kasir / Pembayaran --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Proses Pembayaran</h6>
            <h6 class="m-0 font-weight-bold text-danger">Total Tagihan: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.transactions.updatePayment', $transaction->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payment_method_id">Metode Bayar</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}" {{ $transaction->payment_method_id == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="amount_paid">Jumlah Dibayar</label>
                             <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="amount_paid" id="amount_paid" class="form-control" value="{{ $transaction->amount_paid ?? 0 }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payment_status_id">Status Pembayaran</label>
                            <select name="payment_status_id" id="payment_status_id" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                @foreach ($paymentStatuses as $status)
                                     <option value="{{ $status->id }}" {{ $transaction->payment_status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Update Pembayaran</button>
            </form>
        </div>
    </div>


    {{-- Tombol Aksi Final --}}
    <div class="d-flex justify-content-end">
    {{-- ... (tombol kembali) ... --}}
@endsection