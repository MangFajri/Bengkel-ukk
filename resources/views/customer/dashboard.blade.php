@extends('layouts.customer') {{-- PENTING: Gunakan layout customer --}}

@section('title', 'Dashboard Pelanggan')

@section('content')
    <div class="row">
        {{-- Card Jumlah Kendaraan --}}
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Jumlah Kendaraan Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customer->vehicles->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Jumlah Transaksi --}}
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Riwayat Servis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customer->customerTransactions->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-history fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Selamat Datang, {{ $customer->name }}!</h6>
        </div>
        <div class="card-body">
            <p>Ini adalah halaman dasbor pribadi Anda. Di sini Anda dapat melihat riwayat servis dan mengelola data kendaraan Anda.</p>
            <h5 class="mt-4">Kendaraan Anda:</h5>
            <ul>
                @forelse ($customer->vehicles as $vehicle)
                    <li>{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})</li>
                @empty
                    <li>Anda belum mendaftarkan kendaraan.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection