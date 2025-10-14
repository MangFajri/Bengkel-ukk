@extends('layouts.admin')

@section('title', 'Buat Transaksi Baru')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formulir Transaksi Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.transactions.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="customer_id">Pilih Pelanggan</label>
                <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Nama Pelanggan --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="vehicle_id">Pilih Kendaraan</label>
                <select name="vehicle_id" id="vehicle_id" class="form-control @error('vehicle_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kendaraan (No. Polisi) --</option>
                    {{-- Idealnya, pilihan ini akan muncul dinamis setelah pelanggan dipilih menggunakan JavaScript --}}
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->plate_number }} ({{ $vehicle->brand }} {{ $vehicle->model }})
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="mechanic_id">Tugaskan Mekanik</label>
                <select name="mechanic_id" id="mechanic_id" class="form-control @error('mechanic_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Mekanik --</option>
                    @foreach ($mechanics as $mechanic)
                        <option value="{{ $mechanic->id }}" {{ old('mechanic_id') == $mechanic->id ? 'selected' : '' }}>
                            {{ $mechanic->name }}
                        </option>
                    @endforeach
                </select>
                @error('mechanic_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

             <div class="form-group mb-3">
                <label for="notes">Catatan Awal / Keluhan Pelanggan (Opsional)</label>
                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">Buat Transaksi</button>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection