@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-100 font-weight-bold border-left-warning pl-3">Audit Trail / Log Aktivitas</h1>
    </div>

    <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-history mr-1"></i> Rekaman Aktivitas Terbaru
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-gray-300" width="100%" cellspacing="0" style="color: #e2e8f0;">
                    <thead style="background-color: #0f172a; color: #fff;">
                        <tr>
                            <th style="border-bottom: 2px solid #334155;">Waktu</th>
                            <th style="border-bottom: 2px solid #334155;">Pelaku (User)</th>
                            <th style="border-bottom: 2px solid #334155;">Aktivitas</th>
                            <th style="border-bottom: 2px solid #334155;">Detail (Metadata)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr style="border-bottom: 1px solid #334155;">
                                <td class="small text-gray-400">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}<br>
                                    <span class="text-muted">{{ $log->created_at->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-info">{{ $log->user->name ?? 'System/Deleted' }}</span><br>
                                    <small class="badge badge-dark border border-secondary">{{ $log->user->role ?? '-' }}</small>
                                </td>
                                <td class="text-white">{{ $log->action }}</td>
                                <td class="small text-monospace text-gray-500">
                                    {{-- Tampilkan JSON metadata kalau ada, tapi dipotong biar ga kepanjangan --}}
                                    @if(!empty($log->metadata))
                                        {{ Str::limit(json_encode($log->metadata), 50) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">Belum ada aktivitas tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-end">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection