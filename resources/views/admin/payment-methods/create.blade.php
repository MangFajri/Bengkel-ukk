@extends('layouts.admin')
@section('title', 'Tambah Metode Pembayaran')
@section('content')
<div class="card shadow">
    <div class="card-header">Formulir Tambah Metode Pembayaran</div>
    <div class="card-body">
        <form action="{{ route('admin.payment-methods.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="name">Nama Metode</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group mb-3">
                <label for="details">Detail (Opsional)</label>
                <textarea name="details" id="details" class="form-control">{{ old('details') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection