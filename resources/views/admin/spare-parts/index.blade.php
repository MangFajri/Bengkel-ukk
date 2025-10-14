@extends('layouts.admin')

@section('title', 'Manajemen Sparepart')

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
            <a href="{{ route('admin.spare-parts.create') }}" class="btn btn-primary">Tambah Sparepart Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>SKU</th>
                            <th>Nama Sparepart</th>
                            <th>Brand</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spareParts as $sparePart)
                            <tr>
                                <td>{{ $loop->iteration + $spareParts->firstItem() - 1 }}</td>
                                <td>{{ $sparePart->sku }}</td>
                                <td>{{ $sparePart->name }}</td>
                                <td>{{ $sparePart->brand }}</td>
                                <td>Rp {{ number_format($sparePart->sell_price, 0, ',', '.') }}</td>
                                <td>{{ $sparePart->stock }}</td>
                                <td>
                                    @if ($sparePart->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.spare-parts.edit', $sparePart->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.spare-parts.destroy', $sparePart->id) }}" method="POST"
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
                                <td colspan="8" class="text-center">Data Sparepart tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Link Paginasi -->
            <div class="mt-3">
                {{ $spareParts->links() }}
            </div>
        </div>
    </div>
@endsection