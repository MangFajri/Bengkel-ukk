@extends('layouts.admin')

@section('title', 'Edit Jasa')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Jasa</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Metode HTTP untuk update --}}

            <div class="form-group mb-3">
                <label for="name">Nama Jasa</label>
                {{-- old('name', $service->name) akan menampilkan data lama jika validasi gagal, jika tidak, tampilkan data dari database --}}
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $service->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="code">Kode Jasa (Opsional)</label>
                <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $service->code) }}">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="price">Harga</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $service->price) }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="description">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                {{-- old() tidak bekerja baik dengan checkbox, jadi kita pakai logika biasa --}}
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $service->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Aktif
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection