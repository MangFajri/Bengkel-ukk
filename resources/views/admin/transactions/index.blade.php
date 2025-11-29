@extends('layouts.admin')

@section('title', 'Manajemen Transaksi')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold font-display text-uppercase">Riwayat Service & Transaksi</h1>
        
        <a href="{{ route('admin.transactions.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-2"></i>Input Transaksi Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 text-white mb-4 shadow-sm" style="background-color: #16a34a;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 text-white mb-4 shadow-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4" style="border: 1px solid #e3e6f0;">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Data Transaksi Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    
                    <thead style="background-color: #1e293b; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Kendaraan</th>
                            <th>Status Service</th>
                            <th>Pembayaran</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr>
                            <td class="font-weight-bold text-primary">#{{ $trx->id }}</td>
                            
                            <td>
                                {{ $trx->created_at->format('d M Y') }}
                                <br>
                                <small class="text-muted">{{ $trx->created_at->format('H:i') }} WIB</small>
                            </td>

                            <td>
                                @if($trx->customer)
                                    <span class="font-weight-bold">{{ $trx->customer->name }}</span>
                                    @if($trx->customer->trashed())
                                        <span class="badge badge-danger" style="font-size: 0.6em;">(Deleted)</span>
                                    @endif
                                    <div class="small text-muted">{{ $trx->customer->phone }}</div>
                                @else
                                    <span class="text-danger font-italic">Data User Hilang</span>
                                @endif
                            </td>

                            <td>
                                @if($trx->vehicle)
                                    <div class="badge badge-secondary text-uppercase">{{ $trx->vehicle->plate_number }}</div>
                                    @if($trx->vehicle->trashed())
                                        <span class="text-danger font-weight-bold" title="Data dihapus">*</span>
                                    @endif
                                    <div class="small mt-1">{{ $trx->vehicle->brand }} {{ $trx->vehicle->model }}</div>
                                @else
                                    <span class="small text-muted">Tanpa Kendaraan</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @php
                                    // 1. Ambil nama dari database jika ada
                                    $statusName = $trx->serviceStatus->name ?? null;
                                    $id = $trx->service_status_id;

                                    // 2. FIX LOGIKA ID MANUAL (Sesuai database kamu)
                                    if (!$statusName) {
                                        if ($id == 1) $statusName = 'Pending';
                                        elseif ($id == 2) $statusName = 'Dikonfirmasi';
                                        elseif ($id == 3) $statusName = 'Sedang Dikerjakan'; // ID 3 = Working
                                        elseif ($id == 4) $statusName = 'Selesai';           // ID 4 = Done
                                        elseif ($id == 5) $statusName = 'Sudah Diambil';     // ID 5 = Taken
                                        elseif ($id == 6) $statusName = 'Dibatalkan';
                                        else $statusName = 'Status #' . $id;
                                    }

                                    // 3. Tentukan Warna Badge
                                    $statusClass = 'secondary';
                                    $checkName = strtolower($statusName);

                                    if(str_contains($checkName, 'selesai') || str_contains($checkName, 'diambil')) 
                                        $statusClass = 'success'; // Hijau
                                    elseif(str_contains($checkName, 'kerja') || str_contains($checkName, 'working')) 
                                        $statusClass = 'info';    // Biru Muda
                                    elseif(str_contains($checkName, 'batal')) 
                                        $statusClass = 'danger';  // Merah
                                    elseif(str_contains($checkName, 'tunggu') || str_contains($checkName, 'pending')) 
                                        $statusClass = 'warning'; // Kuning
                                @endphp
                                
                                <span class="badge badge-{{ $statusClass }} px-2 py-1" style="font-size: 0.85rem;">
                                    {{ $statusName }}
                                </span>
                                
                                @if($trx->mechanic)
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-wrench fa-xs"></i> {{ $trx->mechanic->name }}
                                    </div>
                                @else
                                    <div class="small text-danger mt-1 font-italic">Mekanik belum ada</div>
                                @endif
                            </td>

                            <td class="text-center">
                                @if(($trx->paymentStatus->code ?? '') == 'paid')
                                    <span class="badge badge-success">LUNAS</span>
                                @else
                                    <span class="badge badge-danger">BELUM LUNAS</span>
                                @endif
                                <div class="font-weight-bold mt-1 text-gray-800">
                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.transactions.edit', $trx->id) }}" class="btn btn-primary btn-sm" title="Proses & Bayar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.transactions.print', $trx->id) }}" target="_blank" class="btn btn-info btn-sm" title="Cetak Struk">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST" class="d-inline" onsubmit="return confirm('PERINGATAN: Menghapus transaksi akan MENGEMBALIKAN STOK barang. Lanjutkan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-gray-500">
                                <img src="{{ asset('img/undraw_empty.svg') }}" style="height: 100px; opacity: 0.5;" class="mb-3 d-block mx-auto">
                                Belum ada data transaksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[ 0, "desc" ]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush