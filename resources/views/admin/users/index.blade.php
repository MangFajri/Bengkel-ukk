@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-100 font-weight-bold border-left-danger pl-3">Manajemen Hak Akses & User</h1>
        <a href="{{ route('admin.users.create') }}" class="d-none d-sm-inline-block btn btn-danger shadow-sm">
            <i class="fas fa-user-plus fa-sm text-white-50 mr-2"></i> Tambah User Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-left-success shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" 
             style="background-color: #1e293b; border-bottom: 1px solid #334155;">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-users-cog mr-1"></i> Daftar Pengguna Sistem
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-gray-300" id="dataTable" width="100%" cellspacing="0" style="color: #e2e8f0;">
                    <thead style="background-color: #0f172a; color: #fff;">
                        <tr>
                            <th width="5%" style="border-bottom: 2px solid #334155; border-top: none;">No</th>
                            <th style="border-bottom: 2px solid #334155; border-top: none;">Nama Lengkap</th>
                            <th style="border-bottom: 2px solid #334155; border-top: none;">Email / Kontak</th>
                            <th class="text-center" style="border-bottom: 2px solid #334155; border-top: none;">Role (Hak Akses)</th>
                            <th class="text-center" style="border-bottom: 2px solid #334155; border-top: none;">Status</th>
                            <th width="15%" class="text-center" style="border-bottom: 2px solid #334155; border-top: none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr style="border-bottom: 1px solid #334155;">
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">
                                    <div class="font-weight-bold text-white">{{ $user->name }}</div>
                                    <small class="text-gray-500">Joined: {{ $user->created_at->format('d M Y') }}</small>
                                </td>
                                <td class="align-middle">
                                    <div class="text-info">{{ $user->email }}</div>
                                    <small class="text-gray-400">{{ $user->phone ?? '-' }}</small>
                                </td>
                                
                                {{-- ROLE BADGE --}}
                                <td class="align-middle text-center">
                                    @if($user->role === 'admin')
                                        <span class="badge badge-danger px-3 py-2 border border-danger shadow-sm">
                                            <i class="fas fa-user-shield mr-1"></i> ADMINISTRATOR
                                        </span>
                                    @elseif($user->role === 'mechanic')
                                        <span class="badge badge-warning text-dark px-3 py-2 border border-warning shadow-sm">
                                            <i class="fas fa-wrench mr-1"></i> MEKANIK
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2 border border-secondary">
                                            <i class="fas fa-user mr-1"></i> CUSTOMER
                                        </span>
                                    @endif
                                </td>

                                <td class="align-middle text-center">
                                    @if($user->is_active)
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    @endif
                                </td>

                                <td class="align-middle text-center">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm btn-circle" title="Edit Role & Password">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    
                                    {{-- Tombol Hapus (Jangan hapus diri sendiri) --}}
                                    @if(Auth::id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Nonaktifkan user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-circle" title="Hapus/Nonaktifkan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
             <div class="mt-4 d-flex justify-content-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection