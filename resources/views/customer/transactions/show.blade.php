@extends('layouts.customer')

@section('title', 'Detail Servis TRX-' . $transaction->id)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi #TRX-{{ $transaction->id }}</h6>
                    <a href="{{ route('customer.transactions.index') }}" class="btn btn-secondary btn-sm">Kembali ke Riwayat</a>
                </div>
                <div class="card-body">
                    {{-- Informasi Utama --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            {{-- PENTING: Pastikan casts datetime sudah ada di Model Transaction --}}
                            <strong>Tanggal Masuk:</strong> {{ $transaction->check_in_at ? $transaction->check_in_at->format('d F Y, H:i') : '-' }} <br>
                            
                            {{-- Gunakan optional() atau null coalescing agar tidak error jika relasi kosong --}}
                            <strong>Status Servis:</strong> 
                            <span class="badge badge-info">{{ $transaction->serviceStatus->label ?? 'Pending' }}</span> <br>
                            
                            <strong>Status Pembayaran:</strong> 
                            <span class="badge badge-success">{{ $transaction->paymentStatus->label ?? '-' }}</span>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <strong>Kendaraan:</strong> {{ $transaction->vehicle->brand ?? '' }} {{ $transaction->vehicle->model ?? '' }}<br>
                            <strong>No. Polisi:</strong> {{ $transaction->vehicle->plate_number ?? '-' }}<br>
                            <strong>Mekanik:</strong> {{ $transaction->mechanic->name ?? 'Belum Ditentukan' }}
                        </div>
                    </div>

                    <hr>

                    {{-- Rincian Jasa --}}
                    <h5 class="mt-4">Rincian Jasa</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Jasa</th>
                                    <th class="text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- PERBAIKAN 1: Gunakan 'services' (sesuai nama fungsi di Model) --}}
                                @forelse ($transaction->services as $service)
                                    <tr>
                                        {{-- Karena Many-to-Many, objeknya langsung $service --}}
                                        <td>{{ $service->name }}</td>
                                        {{-- Harga ada di tabel pivot --}}
                                        <td class="text-right">Rp {{ number_format($service->pivot->price_at_time, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted">Tidak ada jasa yang dipilih</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Rincian Sparepart --}}
                    <h5 class="mt-4">Rincian Sparepart</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Sparepart</th>
                                    <th>Jumlah</th>
                                    <th class="text-right">Harga Satuan</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- PERBAIKAN 2: Gunakan 'spareParts' (sesuai nama fungsi di Model) --}}
                                @forelse ($transaction->spareParts as $detail)
                                    <tr>
                                        <td>
                                            {{-- Relasi ke tabel spare_parts dari detail transaksi --}}
                                            {{ $detail->sparePart->name ?? 'Item Terhapus' }}
                                            @if($detail->serial_number)
                                                <small class="d-block text-muted">No. Seri: {{ $detail->serial_number }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $detail->qty }}</td>
                                        <td class="text-right">Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($detail->price_at_time * $detail->qty, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">Tidak ada sparepart tambahan</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    {{-- Total Pembayaran --}}
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th>Subtotal</th>
                                        <td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    {{-- Cek jika relasi promo ada --}}
                                    @if($transaction->promo_id && $transaction->promo)
                                    <tr>
                                        <th>Diskon ({{ $transaction->promo->name }})</th>
                                        <td class="text-right text-danger">-</td>
                                    </tr>
                                    @endif
                                    <tr class="font-weight-bold">
                                        <th>Total Akhir</th>
                                        <td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection