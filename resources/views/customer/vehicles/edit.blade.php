@extends('layouts.customer')
@section('title', 'Edit Kendaraan')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Kendaraan</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('customer.vehicles.update', $vehicle->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="plate_number">Nomor Polisi</label>
                <input type="text" name="plate_number" id="plate_number" class="form-control @error('plate_number') is-invalid @enderror" value="{{ old('plate_number', $vehicle->plate_number) }}" required>
                @error('plate_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label for="brand">Merek</label>
                    <input type="text" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand', $vehicle->brand) }}" required>
                    @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 form-group mb-3">
                    <label for="model">Model</label>
                    <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model', $vehicle->model) }}" required>
                    @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="year">Tahun</label>
                <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $vehicle->year) }}" required>
                @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('customer.vehicles.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection