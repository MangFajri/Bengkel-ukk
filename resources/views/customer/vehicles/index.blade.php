@extends('layouts.customer')

@section('title', 'Kendaraan Saya')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white font-weight-bold font-display text-uppercase">Garasi Saya</h1>
        <a href="{{ route('customer.vehicles.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-dark mr-2"></i>Tambah Mobil
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 text-white" role="alert" style="background-color: #16a34a;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Mobil Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No. Polisi</th>
                            <th>Merk & Model</th>
                            <th>Warna</th>
                            <th>Tahun</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $vehicle)
                            <tr>
                                <td class="font-weight-bold text-white">{{ $vehicle->plate_number }}</td>

                                <td>{{ $vehicle->brand }} - {{ $vehicle->model }}</td>
                                <td>{{ $vehicle->color ?? '-' }}</td>
                                <td>{{ $vehicle->year }}</td>
                                <td class="text-center">
                                    <a href="{{ route('customer.vehicles.edit', $vehicle->id) }}"
                                        class="btn btn-warning btn-circle btn-sm" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('customer.vehicles.destroy', $vehicle->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin hapus mobil ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-circle btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection