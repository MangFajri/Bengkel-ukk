@extends('layouts.customer')

@section('title', 'Kendaraan Saya')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white font-weight-bold font-display text-uppercase">Garasi Saya</h1>
        <a href="{{ route('customer.vehicles.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-2"></i>Tambah Mobil
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 text-white mb-4" role="alert" style="background-color: #1cc88a;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" 
             style="background-color: #1e293b; border-bottom: 1px solid #334155;">
            <h6 class="m-0 font-weight-bold text-primary">List Mobil Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-gray-300" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-white" style="border-bottom: 2px solid #475569;">
                            <th>No. Polisi</th>
                            <th>Merk & Model</th>
                            <th>Warna</th>
                            <th>Tahun</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $vehicle)
                            <tr style="border-bottom: 1px solid #334155;">
                                <td class="font-weight-bold text-white align-middle">
                                    <span class="badge badge-light px-2 py-1">{{ $vehicle->plate_number }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold">{{ $vehicle->brand }}</div>
                                    <small>{{ $vehicle->model }}</small>
                                </td>
                                <td class="align-middle">{{ $vehicle->color ?? '-' }}</td>
                                <td class="align-middle">{{ $vehicle->year }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('customer.vehicles.edit', $vehicle->id) }}" 
                                       class="btn btn-warning btn-circle btn-sm mr-1" title="Edit">
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
                            <tr>
                                <td colspan="5" class="text-center py-5 text-gray-500">
                                    <i class="fas fa-car fa-3x mb-3"></i><br>
                                    Belum ada kendaraan terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection