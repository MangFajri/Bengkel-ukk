@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-100 font-weight-bold">Edit Pengguna: <span class="text-primary">{{ $user->name }}</span></h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-2"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Data</h6>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="text-gray-300 font-weight-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" required
                                   style="background-color: #0f172a; border: 1px solid #475569; color: #fff;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="text-gray-300 font-weight-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required
                                   style="background-color: #0f172a; border: 1px solid #475569; color: #fff;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-gray-300 font-weight-bold">Peran (Role)</label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror"
                                            style="background-color: #0f172a; border: 1px solid #475569; color: #fff;">
                                        <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                                        <option value="mechanic" {{ $user->role == 'mechanic' ? 'selected' : '' }}>Mechanic (Mekanik)</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-gray-300 font-weight-bold">No. Telepon / WA</label>
                                    <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $user->phone) }}" placeholder="0812..."
                                           style="background-color: #0f172a; border: 1px solid #475569; color: #fff;">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-gray-300 font-weight-bold">Alamat Lengkap</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" 
                                      style="background-color: #0f172a; border: 1px solid #475569; color: #fff;">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr style="border-color: #475569;">

                        <div class="form-group">
                            <label class="text-warning font-weight-bold">
                                <i class="fas fa-lock mr-1"></i> Ganti Password (Opsional)
                            </label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Kosongkan jika tidak ingin mengganti password"
                                   style="background-color: #0f172a; border: 1px solid #f6c23e; color: #fff;">
                            <small class="text-gray-500">Minimal 8 karakter.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr style="border-color: #475569;">

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                {{-- Hidden input untuk mengirim nilai 0 jika checkbox tidak dicentang --}}
                                <input type="checkbox" class="custom-control-input" id="isActiveSwitch" name="is_active" value="1" 
                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold text-white" for="isActiveSwitch">Akun Aktif?</label>
                            </div>
                            <small class="text-gray-400">Jika dimatikan, pengguna ini tidak akan bisa login ke dalam sistem.</small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary shadow-sm px-4">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- Card Info Tambahan (Opsional) --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-info">Info Akun</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="img-profile rounded-circle d-flex align-items-center justify-content-center font-weight-bold text-white mx-auto mb-2" 
                             style="width: 80px; height: 80px; background-color: #4e73df; font-size: 2rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h5 class="font-weight-bold text-white">{{ $user->name }}</h5>
                        <div class="badge badge-secondary">{{ $user->role }}</div>
                    </div>
                    
                    <p class="small text-gray-400">
                        <strong>Dibuat pada:</strong><br>
                        {{ $user->created_at->format('d F Y, H:i') }}
                    </p>
                    <p class="small text-gray-400">
                        <strong>Terakhir update:</strong><br>
                        {{ $user->updated_at->format('d F Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection