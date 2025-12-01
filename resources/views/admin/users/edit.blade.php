@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-100 font-weight-bold">Edit Hak Akses User</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-warning">Form Edit User: {{ $user->name }}</h6>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="text-gray-300 font-weight-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control font-weight-bold text-white" 
                                   style="background-color: #0f172a; border: 1px solid #475569;" 
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="text-gray-300 font-weight-bold">Email Address</label>
                            <input type="email" name="email" class="form-control font-weight-bold text-white" 
                                   style="background-color: #0f172a; border: 1px solid #475569;" 
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="text-gray-300 font-weight-bold">Role (Hak Akses)</label>
                            <select name="role" class="form-control font-weight-bold text-white" 
                                    style="background-color: #0f172a; border: 1px solid #f6c23e;">
                                <option value="customer" style="background-color: #1e293b;" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                                <option value="mechanic" style="background-color: #1e293b;" {{ $user->role == 'mechanic' ? 'selected' : '' }}>Mekanik (Teknisi)</option>
                                <option value="admin" style="background-color: #1e293b;" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator (Full Akses)</option>
                            </select>
                            <small class="text-gray-500">*Hati-hati memberikan akses Administrator.</small>
                        </div>

                        <hr style="border-color: #475569; margin: 2rem 0;">

                        <div class="form-group">
                            <label class="text-danger font-weight-bold"><i class="fas fa-key mr-1"></i> Reset Password (Opsional)</label>
                            <input type="password" name="password" class="form-control font-weight-bold text-white" 
                                   style="background-color: #0f172a; border: 1px solid #e74a3b;" 
                                   placeholder="Kosongkan jika tidak ingin mengganti password">
                            <small class="text-gray-500">Isi hanya jika user lupa password. Min 8 karakter.</small>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-warning font-weight-bold px-4 shadow-sm text-dark">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection