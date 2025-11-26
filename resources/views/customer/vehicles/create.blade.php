@extends('layouts.customer')

@section('title', 'Tambah Kendaraan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Mobil Baru</h6>
                <a href="{{ route('customer.vehicles.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('customer.vehicles.store') }}" method="POST">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="brand" class="small text-gray-400 text-uppercase font-weight-bold">Merk (Brand)</label>
                            <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand') }}" placeholder="Contoh: Toyota" required style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                        </div>
                        <div class="col-md-6">
                            <label for="model" class="small text-gray-400 text-uppercase font-weight-bold">Model / Tipe</label>
                            <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}" placeholder="Contoh: Avanza Veloz" required style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="plate_number" class="small text-gray-400 text-uppercase font-weight-bold">Nomor Polisi (Plat)</label>
                        <input type="text" class="form-control font-weight-bold text-uppercase" id="plate_number" name="plate_number" value="{{ old('plate_number') }}" placeholder="B 1234 XYZ" required style="background-color: #0f172a; border: 1px solid #334155; color: white; letter-spacing: 2px;">
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="year" class="small text-gray-400 text-uppercase font-weight-bold">Tahun Pembuatan</label>
                            <input type="number" class="form-control" id="year" name="year" value="{{ old('year', date('Y')) }}" required style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                        </div>
                        <div class="col-md-6">
                            <label for="color" class="small text-gray-400 text-uppercase font-weight-bold">Warna Mobil</label>
                            <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}" placeholder="Hitam Metalik" style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                        </div>
                    </div>

                    <hr class="border-secondary my-4">
                    <h6 class="text-gray-400 text-xs uppercase font-weight-bold mb-3">Detail Mesin (Opsional)</h6>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="engine_number" class="small text-gray-400 text-uppercase font-weight-bold">Nomor Mesin</label>
                            <input type="text" class="form-control" id="engine_number" name="engine_number" value="{{ old('engine_number') }}" placeholder="Cth: 1KR-FE-..." style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                        </div>
                        <div class="col-md-6">
                            <label for="chassis_number" class="small text-gray-400 text-uppercase font-weight-bold">Nomor Rangka</label>
                            <input type="text" class="form-control" id="chassis_number" name="chassis_number" value="{{ old('chassis_number') }}" placeholder="Cth: MHKM1..." style="background-color: #0f172a; border: 1px solid #334155; color: white;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold mt-4">
                        <i class="fas fa-save mr-2"></i> SIMPAN KENDARAAN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection