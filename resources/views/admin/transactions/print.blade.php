<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->id }} - FajriGarage</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #333;
            padding-bottom: 10px;
        }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0; font-size: 12px; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { text-align: left; border-bottom: 1px solid #333; padding: 5px 0; }
        .items-table td { padding: 5px 0; border-bottom: 1px dashed #ddd; }
        
        .totals-table { width: 100%; margin-top: 20px; }
        .totals-table td { padding: 5px 0; }
        .grand-total { border-top: 2px solid #333; border-bottom: 2px solid #333; padding: 10px 0; font-size: 18px; }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        /* Hapus elemen lain saat diprint */
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
        }
    </style>
</head>
<body>

    <!-- Tombol Print (Akan hilang saat diprint) -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #333; color: #fff; border: none; cursor: pointer;">Cetak Struk</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #ccc; border: none; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <h1>FAJRI GARAGE</h1>
        <p>Jl. Raya Bengkel No. 99, Sidoarjo, Jawa Timur</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <table class="info-table">
        <tr>
            <td>No. Faktur: <strong>#INV-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
            <td class="text-right">Tanggal: {{ date('d/m/Y H:i', strtotime($transaction->created_at)) }}</td>
        </tr>
        <tr>
            <td>Pelanggan: {{ $transaction->customer->name }}</td>
            <td class="text-right">Mekanik: {{ $transaction->mechanic->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kendaraan: {{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }}</td>
            <td class="text-right">Plat: {{ $transaction->vehicle->plate_number }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <!-- JASA -->
            @foreach($transaction->services as $svc)
            <tr>
                <td>Jasa: {{ $svc->service->name }}</td>
                <td class="text-right">{{ number_format($svc->price_at_time, 0, ',', '.') }}</td>
                <td class="text-right">{{ $svc->qty }}</td>
                <td class="text-right">{{ number_format($svc->price_at_time * $svc->qty, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            <!-- SPAREPART -->
            @foreach($transaction->spareParts as $part)
            <tr>
                <td>Part: {{ $part->sparePart->name }}</td>
                <td class="text-right">{{ number_format($part->price_at_time, 0, ',', '.') }}</td>
                <td class="text-right">{{ $part->qty }}</td>
                <td class="text-right">{{ number_format($part->price_at_time * $part->qty, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td style="width: 60%;"></td>
            <td>Subtotal</td>
            <td class="text-right">{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-bold grand-total">TOTAL BAYAR</td>
            <td class="text-right text-bold grand-total">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Metode Bayar</td>
            <td class="text-right">{{ $transaction->paymentMethod->name ?? 'Tunai' }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda.</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
        <p>-- Fajri Garage --</p>
    </div>

    <script>
        // Otomatis print saat halaman dibuka
        window.onload = function() { window.print(); }
    </script>
</body>
</html>