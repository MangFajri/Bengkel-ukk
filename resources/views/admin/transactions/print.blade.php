<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaction->code ?? 'TRX-' . $transaction->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            max-width: 210mm;
            /* Ukuran A4 Lebar */
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Utility */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .font-bold {
            font-weight: bold;
        }

        .small {
            font-size: 12px;
            color: #666;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mt-4 {
            margin-top: 30px;
        }

        /* Header / Kop */
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .company-name {
            font-size: 24px;
            font-weight: 800;
            color: #1a202c;
            letter-spacing: 1px;
        }

        .company-details {
            font-size: 12px;
            color: #555;
        }

        /* Invoice Info Grid */
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-col {
            width: 48%;
        }

        .info-title {
            font-weight: bold;
            text-transform: uppercase;
            color: #718096;
            font-size: 11px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        /* Table Items */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #f7fafc;
            color: #2d3748;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #cbd5e0;
            font-size: 12px;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        /* Totals */
        .total-section {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .total-table {
            width: 40%;
            border-collapse: collapse;
        }

        .total-table td {
            padding: 5px 10px;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            background-color: #2d3748;
            color: #fff;
        }

        /* Signatures */
        .signatures {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 30%;
            text-align: center;
        }

        .sig-line {
            margin-top: 60px;
            border-top: 1px solid #333;
        }

        /* Badge Lunas */
        .stamp {
            position: absolute;
            top: 150px;
            right: 50px;
            border: 3px solid #38a169;
            /* Green */
            color: #38a169;
            font-size: 40px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px 20px;
            transform: rotate(-15deg);
            opacity: 0.2;
            pointer-events: none;
        }

        .stamp.unpaid {
            border-color: #e53e3e;
            /* Red */
            color: #e53e3e;
        }

        /* Print Button (Hidden on Print) */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0;
            }

            .header {
                margin-top: 0;
            }
        }

        .btn-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #3182ce;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-weight: bold;
            font-family: sans-serif;
            z-index: 9999;
        }

        .btn-print:hover {
            background: #2b6cb0;
        }
    </style>
</head>

<body>

    <a href="javascript:window.print()" class="btn-print no-print">
        🖨️ Cetak Invoice
    </a>

    @if(($transaction->paymentStatus->code ?? '') == 'paid')
        <div class="stamp">LUNAS</div>
    @else
        <div class="stamp unpaid">BELUM LUNAS</div>
    @endif

    <div class="header">
        <div>
            <div class="company-name">BENGKEL PRO</div>
            <div class="company-details">
                Jl. Raya Koding No. 404, Jakarta Selatan<br>
                Telp: (021) 555-0123 | Email: admin@bengkelpro.com<br>
                Website: www.bengkelpro.com
            </div>
        </div>
        <div class="text-right">
            <h2 style="margin:0; color: #4a5568;">INVOICE / NOTA</h2>
            <p style="margin:5px 0 0; font-size: 14px;">No: <strong>#{{ $transaction->id }}</strong></p>
            <p style="margin:2px 0 0; font-size: 12px; color: #718096;">
                Tanggal: {{ $transaction->created_at->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-col">
            <div class="info-title">DITAGIHKAN KEPADA (CUSTOMER)</div>
            <div class="font-bold">{{ $transaction->customer->name ?? 'Guest / Walk-in' }}</div>
            <div>{{ $transaction->customer->phone ?? '-' }}</div>
            <div class="small mt-1">{{ $transaction->customer->address ?? 'Alamat tidak terdata' }}</div>
        </div>
        <div class="info-col text-right">
            <div class="info-title">DETAIL KENDARAAN & MEKANIK</div>
            <div class="font-bold">
                @if($transaction->vehicle)
                    {{ $transaction->vehicle->plate_number }}
                @else
                    -
                @endif
            </div>
            <div>
                @if($transaction->vehicle)
                    {{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }}
                    ({{ $transaction->vehicle->year }})
                @else
                    Kendaraan Tidak Terdata
                @endif
            </div>
            <div class="small mt-1" style="color: #3182ce;">
                Mekanik: {{ $transaction->mechanic->name ?? 'Belum Ditentukan' }}
            </div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="45%">Deskripsi Item / Jasa</th>
                <th width="15%" class="text-center">Tipe</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="25%" class="text-right">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp

            {{-- Loop Services / Jasa --}}
            @foreach($transaction->services as $service)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>
                        <span class="font-bold">{{ $service->name }}</span>
                        <br><span class="small">{{ Str::limit($service->description, 50) }}</span>
                    </td>
                    <td class="text-center"><span
                            style="background: #ebf8ff; color: #2b6cb0; padding: 2px 6px; border-radius: 4px; font-size: 10px;">JASA</span>
                    </td>
                    <td class="text-center">1</td>
                    <td class="text-right font-bold">{{ number_format($service->pivot->price_at_time, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            {{-- Loop Spareparts --}}
            @foreach($transaction->spareParts as $part)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>
                        <span class="font-bold">{{ $part->name }}</span>
                    </td>
                    <td class="text-center"><span
                            style="background: #faf5ff; color: #553c9a; padding: 2px 6px; border-radius: 4px; font-size: 10px;">BARANG</span>
                    </td>
                    <td class="text-center">{{ $part->pivot->qty }}</td>
                    <td class="text-right font-bold">
                        {{ number_format($part->pivot->price_at_time * $part->pivot->qty, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            {{-- Row Kosong biar tabel terlihat penuh (Opsional) --}}
            @if(($transaction->services->count() + $transaction->spareParts->count()) < 5)
                @for($i = 0; $i < (5 - ($transaction->services->count() + $transaction->spareParts->count())); $i++)
                    <tr>
                        <td style="color: white">.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            @endif
        </tbody>
    </table>

    <div class="info-grid">
        <div style="width: 55%;">
            <div class="info-title">CATATAN MEKANIK / KASIR</div>
            <div
                style="font-style: italic; color: #555; background: #f7fafc; padding: 10px; border-radius: 5px; min-height: 60px;">
                {{ $transaction->notes ?? 'Tidak ada catatan khusus.' }}
            </div>

            <div class="info-title mt-4">METODE PEMBAYARAN</div>
            <div>
                {{ $transaction->paymentMethod->name ?? '-' }}
                @if($transaction->payment_status_id == 1)
                    (Lunas)
                @endif
            </div>
        </div>

        <div class="total-section" style="width: 40%;">
            <table class="total-table">
                <tr>
                    <td class="text-right font-bold">Total:</td>
                    <td class="text-right grand-total">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
                @if($transaction->payment_status_id == 1)
                    <tr>
                        <td class="text-right small">Bayar:</td>
                        <td class="text-right small">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-right small">Kembali:</td>
                        <td class="text-right small">Rp
                            {{ number_format($transaction->amount_paid - $transaction->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="signatures">
        <div class="sig-box">
            <p>Penerima / Pelanggan</p>
            <div class="sig-line"></div>
            <p class="font-bold">{{ $transaction->customer->name ?? 'Pelanggan' }}</p>
        </div>

        {{-- TANDA TANGAN MEKANIK (BARU) --}}
        <div class="sig-box">
            <p>Mekanik</p>
            <div class="sig-line"></div>
            <p class="font-bold">{{ $transaction->mechanic->name ?? '-' }}</p>
        </div>

        {{-- TANDA TANGAN ADMIN/KASIR (BARU) --}}
        <div class="sig-box">
            <p>Admin / Kasir</p>
            <div class="sig-line"></div>
            <p class="font-bold">{{ $transaction->createdBy->name ?? 'Sistem' }}</p>
        </div>
    </div>

    <div class="text-center mt-4 small" style="border-top: 1px dashed #ccc; padding-top: 10px;">
        <p>Terima kasih atas kepercayaan Anda. Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.<br>
            Garansi Service berlaku 1 minggu sejak tanggal nota.</p>
    </div>

</body>

</html>