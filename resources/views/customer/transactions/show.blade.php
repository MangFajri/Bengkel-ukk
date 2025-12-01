@extends('layouts.customer')

@section('title', 'Detail Servis TRX-' . $transaction->id)

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155; color: #e2e8f0;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                    style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Transaksi #TRX-{{ $transaction->id }}</h6>
                    <a href="{{ route('customer.transactions.index') }}" class="btn btn-secondary btn-sm shadow-sm">Kembali
                        ke Riwayat</a>
                </div>
                <div class="card-body">
                    {{-- Informasi Utama --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Tanggal Masuk:</strong>
                            {{ $transaction->check_in_at ? $transaction->check_in_at->format('d F Y, H:i') : '-' }} <br>

                            <strong>Status Servis:</strong>
                            <span class="badge badge-info">{{ $transaction->serviceStatus->label ?? 'Pending' }}</span> <br>

                            <strong>Status Pembayaran:</strong>
                            <span class="badge badge-{{ $transaction->payment_status_id == 1 ? 'success' : 'warning' }}">
                                {{ $transaction->paymentStatus->label ?? '-' }}
                            </span>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <strong>Kendaraan:</strong> {{ $transaction->vehicle->brand ?? '' }}
                            {{ $transaction->vehicle->model ?? '' }}<br>
                            <strong>No. Polisi:</strong> {{ $transaction->vehicle->plate_number ?? '-' }}<br>
                            <strong>Mekanik:</strong> {{ $transaction->mechanic->name ?? 'Belum Ditentukan' }}
                        </div>
                    </div>

                    <hr style="border-color: #475569;">

                    {{-- Rincian Jasa --}}
                    <h5 class="mt-4 text-info">Rincian Jasa</h5>
                    <div class="table-responsive">
                        <table class="table text-gray-300">
                            <thead>
                                <tr>
                                    <th style="border-top: none; border-bottom: 1px solid #475569;">Nama Jasa</th>
                                    <th class="text-right" style="border-top: none; border-bottom: 1px solid #475569;">Harga
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaction->services as $service)
                                    <tr>
                                        <td style="border-color: #475569;">{{ $service->name }}</td>
                                        <td class="text-right" style="border-color: #475569;">Rp
                                            {{ number_format($service->pivot->price_at_time, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted" style="border-color: #475569;">Tidak ada
                                            jasa yang dipilih</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Rincian Sparepart --}}
                    <h5 class="mt-4 text-warning">Rincian Sparepart</h5>
                    <div class="table-responsive">
                        <table class="table text-gray-300">
                            <thead>
                                <tr>
                                    <th style="border-top: none; border-bottom: 1px solid #475569;">Nama Sparepart</th>
                                    <th style="border-top: none; border-bottom: 1px solid #475569;">Jumlah</th>
                                    <th class="text-right" style="border-top: none; border-bottom: 1px solid #475569;">Harga
                                        Satuan</th>
                                    <th class="text-right" style="border-top: none; border-bottom: 1px solid #475569;">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaction->spareParts as $detail)
                                    <tr>
                                        <td style="border-color: #475569;">
                                            {{ $detail->name }}
                                            @if($detail->pivot->serial_number ?? false)
                                                <small class="d-block text-muted">No. Seri:
                                                    {{ $detail->pivot->serial_number }}</small>
                                            @endif
                                        </td>
                                        <td style="border-color: #475569;">{{ $detail->pivot->qty }}</td>
                                        <td class="text-right" style="border-color: #475569;">Rp
                                            {{ number_format($detail->pivot->price_at_time, 0, ',', '.') }}</td>
                                        <td class="text-right" style="border-color: #475569;">
                                            Rp
                                            {{ number_format($detail->pivot->price_at_time * $detail->pivot->qty, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted" style="border-color: #475569;">Tidak ada
                                            sparepart tambahan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr style="border-color: #475569;">

                    {{-- Total Pembayaran --}}
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <table class="table table-sm text-gray-300">
                                <tbody>
                                    <tr>
                                        <th style="border: none;">Subtotal</th>
                                        <td class="text-right" style="border: none;">Rp
                                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($transaction->promo_id && $transaction->promo)
                                        <tr>
                                            <th style="border: none;">Diskon ({{ $transaction->promo->name }})</th>
                                            <td class="text-right text-danger" style="border: none;">-</td>
                                        </tr>
                                    @endif
                                    <tr class="font-weight-bold" style="font-size: 1.2em;">
                                        <th style="border-top: 1px solid #475569;">Total Akhir</th>
                                        <td class="text-right" style="border-top: 1px solid #475569;">Rp
                                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155; color: #e2e8f0;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-receipt mr-1"></i> Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            @if($transaction->payment_status_id == 1)
                                <div class="alert alert-success mb-0 text-center"
                                    style="background-color: #064e3b; border-color: #065f46; color: #a7f3d0;">
                                    <i class="fas fa-check-circle fa-3x mb-2"></i><br>
                                    <h4 class="font-weight-bold">LUNAS</h4>
                                    <p class="mb-0">Terima kasih, pembayaran Anda telah dikonfirmasi.</p>
                                </div>
                            @else
                                <h5 class="font-weight-bold text-danger mb-3">Menunggu Pembayaran</h5>
                                <p class="mb-2 text-gray-400">Silakan transfer sesuai total tagihan ke:</p>

                                {{-- [FIX] BOX REKENING: DARK MODE --}}
                                <div class="p-3 rounded border mb-3"
                                    style="background-color: #0f172a; border-color: #334155 !important; color: #e2e8f0;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-warning font-weight-bold"><i
                                                class="fas fa-university mr-2"></i>BCA</span>
                                        <span class="font-mono h5 mb-0">123-456-7890</span>
                                    </div>
                                    <div class="small text-muted text-right mb-3">a.n Bengkel Pro</div>

                                    <div class="d-flex justify-content-between align-items-center mb-2 border-top pt-2"
                                        style="border-color: #334155 !important;">
                                        <span class="text-warning font-weight-bold"><i
                                                class="fas fa-university mr-2"></i>Mandiri</span>
                                        <span class="font-mono h5 mb-0">098-765-4321</span>
                                    </div>
                                    <div class="small text-muted text-right">a.n Bengkel Pro</div>
                                </div>

                                <p class="small text-muted mt-2"><i class="fas fa-info-circle mr-1"></i> Setelah transfer,
                                    silakan upload foto bukti di samping.</p>
                            @endif
                        </div>

                        <div class="col-md-6 text-center border-left" style="border-color: #334155 !important;">
                            @if($transaction->payment_proof)
                                {{-- Jika sudah ada bukti --}}
                                <label class="d-block small font-weight-bold text-gray-400">Bukti Transfer Terkirim:</label>
                                <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $transaction->payment_proof) }}"
                                        class="img-fluid rounded border shadow-sm mb-3"
                                        style="max-height: 200px; border-color: #475569 !important;" alt="Bukti Bayar">
                                </a>
                                @if($transaction->payment_status_id != 1)
                                    <div class="alert alert-info py-2 small"
                                        style="background-color: #1e3a8a; border-color: #1e40af; color: #bfdbfe;">
                                        <i class="fas fa-clock mr-1"></i> Sedang diperiksa Admin.
                                    </div>
                                    {{-- Tombol Ganti Bukti --}}
                                    <button class="btn btn-sm btn-outline-secondary mt-2" type="button" data-toggle="collapse"
                                        data-target="#reuploadForm">
                                        Ganti Gambar?
                                    </button>
                                @endif
                            @endif

                            {{-- Form Upload --}}
                            <div class="collapse {{ !$transaction->payment_proof ? 'show' : '' }} mt-3" id="reuploadForm">
                                @if($transaction->payment_status_id != 1)
                                    <form action="{{ route('customer.transactions.uploadProof', $transaction->id) }}"
                                        method="POST" enctype="multipart/form-data" class="p-3 border rounded border-dashed"
                                        style="background-color: #0f172a; border-color: #475569 !important; border-style: dashed !important;">
                                        @csrf

                                        {{-- Tampilkan Alert Global Jika Ada Error Apapun --}}
                                        @if ($errors->any())
                                            <div class="alert alert-danger small mb-3">
                                                <ul class="mb-0 pl-3">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label class="font-weight-bold text-gray-300">Upload Bukti Transfer</label>

                                            {{-- Input File dengan Validasi Class --}}
                                            <input type="file" name="payment_proof"
                                                class="form-control-file text-gray-400 @error('payment_proof') is-invalid @enderror"
                                                required>

                                            {{-- Pesan Error Spesifik untuk Input Ini --}}
                                            @error('payment_proof')
                                                <div class="invalid-feedback d-block text-danger mt-2 small">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror

                                            <small class="form-text text-muted">Format: JPG, PNG (Max 2MB)</small>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                                            <i class="fas fa-upload mr-1"></i> Kirim Bukti
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection