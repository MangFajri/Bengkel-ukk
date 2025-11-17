@extends('layouts.admin')
@section('title', 'Edit Metode Pembayaran')
@section('content')
<div class="card shadow">
    <div class="card-header">Formulir Edit Metode Pembayaran</div>
    <div class="card-body">
        <form action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="name">Nama Metode</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $paymentMethod->name) }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group mb-3">
                <label for="details">Detail (Opsional)</label>
                <textarea name="details" id="details" class="form-control">{{ old('details', $paymentMethod->details) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection