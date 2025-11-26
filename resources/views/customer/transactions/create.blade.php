@extends('layouts.customer')

@section('title', 'Booking Service Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        
        <!-- Header dengan tombol kembali -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-white font-weight-bold font-display text-uppercase">Booking Service</h1>
            <a href="{{ route('customer.transactions.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-2"></i>Kembali
            </a>
        </div>

        <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header py-3 border-bottom border-secondary" style="background-color: #1e293b;">
                <h6 class="m-0 font-weight-bold text-primary text-uppercase">Formulir Booking</h6>
            </div>
            <div class="card-body text-white">
                
                <form action="{{ route('customer.transactions.store') }}" method="POST">
                    @csrf
                    
                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 mb-4" style="background-color: #ef4444; color: white;">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Pilih Kendaraan -->
                    <div class="form-group mb-4">
                        <label class="small text-gray-400 text-uppercase font-weight-bold">Pilih Kendaraan</label>
                        <select name="vehicle_id" class="form-control" style="background-color: #0f172a; border: 1px solid #334155; color: white; height: 50px;">
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">
                                    {{ $vehicle->brand }} {{ $vehicle->model }} - [ {{ $vehicle->plate_number }} ]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rencana Tanggal Booking -->
                    <div class="form-group mb-4">
                        <label class="small text-gray-400 text-uppercase font-weight-bold">Rencana Tanggal Datang</label>
                        <input type="date" name="check_in_date" class="form-control" min="{{ date('Y-m-d') }}" required 
                               style="background-color: #0f172a; border: 1px solid #334155; color: white; height: 50px;">
                        <small class="text-muted">Kami buka setiap hari pukul 08:00 - 17:00 WIB.</small>
                    </div>

                    <!-- Pilih Layanan (Checkbox Grid) -->
                    <div class="form-group mb-4">
                        <label class="small text-gray-400 text-uppercase font-weight-bold mb-3 d-block">Pilih Layanan Service</label>
                        <div class="row">
                            @foreach($services as $service)
                            <div class="col-md-6 mb-3">
                                <label class="d-flex align-items-center p-3 rounded cursor-pointer hover-bg-slate-800 transition border border-slate-700" style="background-color: #0f172a;">
                                    <input type="checkbox" name="service_ids[]" value="{{ $service->id }}" class="form-checkbox h-5 w-5 text-orange-600 mr-3">
                                    <div>
                                        <span class="d-block font-weight-bold">{{ $service->name }}</span>
                                        <span class="text-xs text-orange-400">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Keluhan / Catatan -->
                    <div class="form-group mb-4">
                        <label class="small text-gray-400 text-uppercase font-weight-bold">Keluhan / Catatan Tambahan</label>
                        <textarea name="notes" rows="4" class="form-control" placeholder="Contoh: Suara mesin kasar saat AC nyala, rem bunyi..." 
                                  style="background-color: #0f172a; border: 1px solid #334155; color: white;"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold text-uppercase tracking-widest mt-5 shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Booking
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection