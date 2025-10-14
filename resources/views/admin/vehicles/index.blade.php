@extends('layouts.admin')

@section('title', 'Manajemen Kendaraan')

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
            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">Tambah Kendaraan Baru</a> 
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Polisi</th>
                            <th>Pemilik</th>
                            <th>Merek & Model</th>
                            <th>Tahun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $vehicle)
                            <tr>
                                <td>{{ $loop->iteration + $vehicles->firstItem() - 1 }}</td>
                                <td>{{ $vehicle->plate_number }}</td>
                                {{-- Menggunakan null safe operator jika pemilik sudah dihapus --}}
                                <td>{{ $vehicle->user->name ?? 'Pemilik Dihapus' }}</td>
                                <td>{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                <td>{{ $vehicle->year }}</td>
                                <td>
                                    <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus kendaraan ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data Kendaraan tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>
@endsection