@extends('layouts.admin')

@section('title', 'Input Service Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background-color: #1e293b;">
                <h6 class="m-0 font-weight-bold text-primary text-uppercase">Formulir Masuk Bengkel (Walk-In & Booking)</h6>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">Batal</a>
            </div>
            <div class="card-body text-white">
                
                <!-- Tampilkan Error Validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.transactions.store') }}" method="POST">
                    @csrf

                    <!-- Pilihan Tipe Pelanggan (Tabs) -->
                    <div class="form-group text-center mb-5">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary active" onclick="toggleCustomerType('existing')">
                                <input type="radio" name="type" value="existing" checked> Pelanggan Terdaftar
                            </label>
                            <label class="btn btn-outline-primary" onclick="toggleCustomerType('walkin')">
                                <input type="radio" name="type" value="walkin"> Pelanggan Baru (Walk-In)
                            </label>
                        </div>
                    </div>

                    <!-- SECTION A: CUSTOMER LAMA -->
                    <div id="section-existing" class="p-4 rounded mb-4 border border-primary" style="background-color: #0f172a;">
                        <h6 class="text-primary font-weight-bold mb-3"><i class="fas fa-user-check mr-2"></i>Cari Data Pelanggan</h6>
                        <div class="form-group">
                            <label>Pilih Pelanggan</label>
                            <select name="customer_id" class="form-control" style="background-color: #1e293b; color: white;">
                                <option value="">-- Cari Nama / Email --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pilih Kendaraan</label>
                            <select name="vehicle_id" class="form-control" style="background-color: #1e293b; color: white;">
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->plate_number }} - {{ $v->brand }} {{ $v->model }} (Milik: {{ $v->user->name }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted text-white-50">*Tips: Gunakan Ctrl+F untuk mencari plat nomor</small>
                        </div>
                    </div>

                    <!-- SECTION B: CUSTOMER BARU (WALK-IN) -->
                    <div id="section-walkin" class="p-4 rounded mb-4 border border-success d-none" style="background-color: #0f172a;">
                        <h6 class="text-success font-weight-bold mb-3"><i class="fas fa-user-plus mr-2"></i>Input Pelanggan Walk-In</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="new_name" class="form-control bg-dark text-white border-secondary" placeholder="Nama Pelanggan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. WhatsApp / HP</label>
                                    <input type="text" name="new_phone" class="form-control bg-dark text-white border-secondary" placeholder="08xxxxx">
                                </div>
                            </div>
                        </div>
                        <hr class="border-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Plat Nomor</label>
                                    <input type="text" name="new_plate_number" class="form-control bg-dark text-white border-secondary text-uppercase" placeholder="B 1234 XYZ">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Merk</label>
                                    <input type="text" name="new_brand" class="form-control bg-dark text-white border-secondary" placeholder="Honda">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Model</label>
                                    <input type="text" name="new_model" class="form-control bg-dark text-white border-secondary" placeholder="Brio Satya">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION UMUM: PILIH LAYANAN -->
                    <div class="p-4 rounded mb-4 border border-secondary" style="background-color: #0f172a;">
                        <h6 class="text-warning font-weight-bold mb-3"><i class="fas fa-tools mr-2"></i>Detail Pengerjaan</h6>
                        
                        <div class="form-group">
                            <label class="d-block mb-2">Pilih Layanan Awal (Bisa pilih banyak)</label>
                            <div class="row">
                                @foreach($services as $service)
                                <div class="col-md-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="svc-{{ $service->id }}" name="service_ids[]" value="{{ $service->id }}">
                                        <label class="custom-control-label" for="svc-{{ $service->id }}">
                                            {{ $service->name }} <span class="badge badge-dark ml-2">Rp {{ number_format($service->price) }}</span>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label>Tunjuk Mekanik (Opsional)</label>
                            <select name="mechanic_id" class="form-control bg-dark text-white border-secondary">
                                <option value="">-- Biarkan Kosong (Pilih Nanti) --</option>
                                @foreach($mechanics as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Catatan / Keluhan</label>
                            <textarea name="notes" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Contoh: Service rutin + ganti oli"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold text-uppercase">
                        <i class="fas fa-save mr-2"></i> Buat Service Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk toggle tab (Disimpan di stack scripts, bukan di content)
    function toggleCustomerType(type) {
        if(type === 'existing') {
            document.getElementById('section-existing').classList.remove('d-none');
            document.getElementById('section-walkin').classList.add('d-none');
        } else {
            document.getElementById('section-existing').classList.add('d-none');
            document.getElementById('section-walkin').classList.remove('d-none');
        }
    }
</script>
@endpush