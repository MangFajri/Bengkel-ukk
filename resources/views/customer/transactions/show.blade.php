@extends('layouts.customer')

@section('title', 'Detail Servis TRX-' . $transaction->id)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi #TRX-{{ $transaction->id }}</h6>
                    <a href="{{ route('customer.transactions.index') }}" class="btn btn-secondary btn-sm">Kembali ke Riwayat</a>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    {{-- Informasi Utama --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Tanggal Masuk:</strong> {{ $transaction->check_in_at->format('d F Y, H:i') }} <br>
                            <strong>Status Servis:</strong> <span class="badge badge-info">{{ $transaction->serviceStatus->label }}</span> <br>
                            <strong>Status Pembayaran:</strong> <span class="badge badge-success">{{ $transaction->paymentStatus->label }}</span>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <strong>Kendaraan:</strong> {{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }}<br>
                            <strong>No. Polisi:</strong> {{ $transaction->vehicle->plate_number }}<br>
                            <strong>Mekanik:</strong> {{ $transaction->mechanic->name ?? 'N/A' }}
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
                                @foreach ($transaction->transactionServices as $detail)
                                    <tr>
                                        <td>{{ $detail->service->name }}</td>
                                        <td class="text-right">Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
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
                                @foreach ($transaction->transactionSpareParts as $detail)
                                    <tr>
                                        <td>
                                            {{ $detail->sparePart->name }}
                                            @if($detail->serial_number)
                                                <small class="d-block text-muted">No. Seri: {{ $detail->serial_number }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $detail->qty }}</td>
                                        <td class="text-right">Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($detail->price_at_time * $detail->qty, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
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
                                    @if($transaction->promo)
                                    <tr>
                                        <th>Diskon ({{ $transaction->promo->name }})</th>
                                        {{-- Logika diskon bisa lebih kompleks, ini contoh sederhana --}}
                                        <td class="text-right text-danger">- Rp ...</td>
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