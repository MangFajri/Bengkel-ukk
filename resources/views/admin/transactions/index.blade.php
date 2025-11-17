@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary">Buat Transaksi Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Mekanik</th>
                            <th>Total Biaya</th>
                            <th>Status Servis</th>
                            <th>Status Bayar</th>
                            <th>Tgl Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>TRX-{{ $transaction->id }}</td>
                                <td>{{ $transaction->customer->name ?? 'N/A' }}</td>
                                <td>{{ $transaction->vehicle->plate_number ?? 'N/A' }}</td>
                                <td>{{ $transaction->mechanic->name ?? 'Belum Ditugaskan' }}</td>
                                <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    {{-- Contoh cara menampilkan label status --}}
                                    <span class="badge badge-info">{{ $transaction->serviceStatus->label ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">{{ $transaction->paymentStatus->label ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $transaction->check_in_at->format('d M Y, H:i') }}</td>
                                <td>
                                    
                                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data transaksi.</td>
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