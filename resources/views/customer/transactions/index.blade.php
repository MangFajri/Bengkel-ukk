@extends('layouts.customer')

@section('title', 'Riwayat Servis Saya')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Riwayat Servis</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Kendaraan</th>
                            <th>Tanggal Masuk</th>
                            <th>Status Servis</th>
                            <th>Total Biaya</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>TRX-{{ $transaction->id }}</td>
                                <td>{{ $transaction->vehicle->plate_number }}</td>
                                <td>{{ $transaction->check_in_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $transaction->serviceStatus->label }}</span>
                                </td>
                                <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('customer.transactions.show', $transaction->id) }}" class="btn btn-sm btn-info">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Anda belum memiliki riwayat servis.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection