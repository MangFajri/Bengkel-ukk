@extends('layouts.mechanic') {{-- PENTING: Gunakan layout mekanik --}}

@section('title', 'Daftar Tugas')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pekerjaan yang Ditugaskan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Tgl Masuk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jobs as $job)
                            <tr>
                                <td>TRX-{{ $job->id }}</td>
                                <td>{{ $job->customer->name }}</td>
                                <td>{{ $job->vehicle->plate_number }}</td>
                                <td>{{ $job->check_in_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <span class="badge badge-{{ $job->serviceStatus->code == 'waiting' ? 'warning' : 'info' }}">
                                        {{ $job->serviceStatus->label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('mechanic.jobs.show', $job->id) }}" class="btn btn-sm btn-primary">
                                        Kerjakan / Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada tugas aktif saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
@endsection