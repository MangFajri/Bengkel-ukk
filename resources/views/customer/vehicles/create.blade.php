@extends('layouts.customer')

@section('title', 'Tambah Kendaraan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" 
                 style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Mobil Baru</h6>
                <a href="{{ route('customer.vehicles.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('customer.vehicles.store') }}" method="POST">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 mb-4">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="brand" class="small text-gray-400 text-uppercase font-weight-bold">Merk (Brand)</label>
                            <input type="text" class="form-control text-white" id="brand" name="brand" 
                                   value="{{ old('brand') }}" placeholder="Cth: Toyota" required
                                   style="background-color: #0f172a; border: 1px solid #334155;">
                        </div>
                        <div class="col-md-6">
                            <label for="model" class="small text-gray-400 text-uppercase font-weight-bold">Model / Tipe</label>
                            <input type="text" class="form-control text-white" id="model" name="model" 
                                   value="{{ old('model') }}" placeholder="Cth: Avanza Veloz" required
                                   style="background-color: #0f172a; border: 1px solid #334155;">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="plate_number" class="small text-gray-400 text-uppercase font-weight-bold">No. Polisi</label>
                            <input type="text" class="form-control text-white text-uppercase" id="plate_number" name="plate_number" 
                                   value="{{ old('plate_number') }}" placeholder="B 1234 ABC" required
                                   style="background-color: #0f172a; border: 1px solid #334155;">
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="year" class="small text-gray-400 text-uppercase font-weight-bold">Tahun</label>
                            <input type="number" class="form-control text-white" id="year" name="year" 
                                   value="{{ old('year') }}" placeholder="2022" required
                                   style="background-color: #0f172a; border: 1px solid #334155;">
                        </div>
                         <div class="col-md-3">
                            <label for="color" class="small text-gray-400 text-uppercase font-weight-bold">Warna</label>
                            <input type="text" class="form-control text-white" id="color" name="color" 
                                   value="{{ old('color') }}" placeholder="Hitam"
                                   style="background-color: #0f172a; border: 1px solid #334155;">
                        </div>
                    </div>

                    <hr class="border-secondary my-4" style="border-color: #334155 !important;">
                    <h6 class="text-gray-400 text-xs uppercase font-weight-bold mb-3">Detail Mesin (Opsional)</h6>

                    <div class="form-group row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="engine_number" class="small text-gray-400 text-uppercase font-weight-bold">Nomor Mesin</label>
                            <input type="text" class="form-control text-white" id="engine_number" name="engine_number" 
                                   value="{{ old('engine_number') }}" placeholder="Cth: 1KR-FE-..."
                                   style="background-color: #0f172a; border: 1px solid #334155;">
                        </div>
                        <div class="col-md-6">
                            <label for="chassis_number" class="small text-gray-400 text-uppercase font-weight-bold">Nomor Rangka</label>
                            <input type="text" class="form-control text-white" id="chassis_number" name="chassis_number" 
                                   value="{{ old('chassis_number') }}" placeholder="Cth: MHKM1..."
                                   style="background-color: #0f172a; border: 1px solid #334155;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold mt-4">
                        <i class="fas fa-save mr-2"></i> Simpan Kendaraan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection