@extends('layouts.admin')

@section('title', 'Manajemen Metode Pembayaran')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary">Tambah Metode Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Metode</th>
                            <th>Detail</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paymentMethods as $method)
                            <tr>
                                <td>{{ $loop->iteration + $paymentMethods->firstItem() - 1 }}</td>
                                <td>{{ $method->name }}</td>
                                <td>{{ $method->details }}</td>
                                <td>
                                    <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.payment-methods.destroy', $method->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $paymentMethods->links() }}
            </div>
        </div>
    </div>
@endsection