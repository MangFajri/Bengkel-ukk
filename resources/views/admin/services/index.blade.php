@extends('layouts.admin')

@section('title', 'Manajemen Jasa')

@section('content')
    {{-- Menampilkan pesan sukses --}}
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
            {{-- Perbarui link ini --}}
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Tambah Jasa Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Jasa</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $service)
                            <tr>
                                <td>{{ $loop->iteration + $services->firstItem() - 1 }}</td>
                                <td>{{ $service->code }}</td>
                                <td>{{ $service->name }}</td>
                                <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($service->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.services.edit', $service->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data Jasa tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Link Paginasi -->
            <div class="mt-3">
                {{ $services->links() }}
            </div>
        </div>
    </div>
@endsection