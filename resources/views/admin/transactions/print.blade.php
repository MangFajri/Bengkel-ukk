<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Service #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Font seperti mesin kasir */
            background-color: #fff;
            color: #000;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 14px; }
        
        .info-table { width: 100%; margin-bottom: 20px; font-size: 14px; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .label { font-weight: bold; width: 120px; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
        .items-table th { border-bottom: 2px solid #000; text-align: left; padding: 5px 0; }
        .items-table td { border-bottom: 1px dashed #ccc; padding: 5px 0; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .totals { margin-top: 20px; text-align: right; font-size: 16px; }
        .grand-total { font-size: 20px; font-weight: bold; border-top: 2px solid #000; padding-top: 10px; margin-top: 5px; }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        /* Agar tombol print tidak ikut ter-print */
        @media print {
            .no-print { display: none; }
        }
        .btn-print {
            background: #007bff; color: white; padding: 10px 20px; 
            text-decoration: none; border-radius: 5px; display: inline-block; margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="text-right no-print">
        <a href="#" onclick="window.print()" class="btn-print">🖨️ Cetak Struk</a>
    </div>

    <div class="header">
        <h1>GARAGE</h1>
        <p>Jl. Koding No. 404, Error City</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">No. Transaksi</td>
            <td>: #{{ $transaction->id }}</td>
            <td class="label">Tanggal</td>
            <td>: {{ $transaction->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Pelanggan</td>
            <td>: {{ $transaction->customer->name ?? 'Umum/Terhapus' }}</td>
            <td class="label">Mekanik</td>
            <td>: {{ $transaction->mechanic->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kendaraan</td>
            <td colspan="3">: 
                @if($transaction->vehicle)
                    {{ $transaction->vehicle->plate_number }} - {{ $transaction->vehicle->brand }} {{ $transaction->vehicle->model }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td colspan="3">: 
                @if(($transaction->paymentStatus->code ?? '') == 'paid')
                    [LUNAS]
                @else
                    [BELUM LUNAS]
                @endif
                - {{ $transaction->serviceStatus->name ?? 'Selesai' }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Keterangan</th>
                <th width="15%" class="text-right">Qty</th>
                <th width="35%" class="text-right">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->services as $item)
            <tr>
                <td>
                    {{ $item->service->name ?? 'Jasa (Terhapus)' }}
                    <div style="font-size: 10px; color: #555;">*Jasa Service</div>
                </td>
                <td class="text-right">1</td>
                <td class="text-right">Rp {{ number_format($item->price_at_time ?? $item->service->price, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            @foreach($transaction->spareParts as $part)
            <tr>
                <td>
                    {{ $part->sparePart->name ?? 'Barang (Terhapus)' }}
                    <div style="font-size: 10px; color: #555;">*Sparepart</div>
                </td>
                <td class="text-right">{{ $part->qty }}</td>
                <td class="text-right">Rp {{ number_format(($part->price_at_time ?? $part->sparePart->sell_price) * $part->qty, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="grand-total">
            Total Biaya: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih atas kepercayaan Anda.</p>
        <p>Garansi service 1 minggu dengan membawa struk ini.</p>
    </div>

    <script>
        // Otomatis muncul popup print saat halaman dibuka
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>