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
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.transactions.store') }}" method="POST">
                    @csrf

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

                    <div id="section-existing" class="p-4 rounded mb-4 border border-primary" style="background-color: #0f172a;">
                        <h6 class="text-primary font-weight-bold mb-3"><i class="fas fa-user-check mr-2"></i>Cari Data Pelanggan</h6>
                        <div class="form-group">
                            <label>Pilih Pelanggan</label>
                            {{-- Tambahkan ID agar bisa dibaca JS --}}
                            <select name="customer_id" id="selectCustomer" class="form-control" style="background-color: #1e293b; color: white;">
                                <option value="">-- Cari Nama / Email --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pilih Kendaraan</label>
                            {{-- Tambahkan ID --}}
                            <select name="vehicle_id" id="selectVehicle" class="form-control" style="background-color: #1e293b; color: white;">
                                <option value="">-- Pilih Pelanggan Terlebih Dahulu --</option>
                                @foreach($vehicles as $v)
                                    {{-- PENTING: Tambahkan data-user agar bisa difilter --}}
                                    <option value="{{ $v->id }}" data-user="{{ $v->user_id }}" class="vehicle-option">
                                        {{ $v->plate_number }} - {{ $v->brand }} {{ $v->model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

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

                    <div class="p-4 rounded mb-4 border border-secondary" style="background-color: #0f172a;">
                        <h6 class="text-warning font-weight-bold mb-3"><i class="fas fa-tools mr-2"></i>Detail Pengerjaan & Mekanik</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Tanggal Transaksi</label>
                                <input type="date" name="date" class="form-control bg-dark text-white border-secondary" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Tunjuk Mekanik (Opsional)</label>
                                <select name="mechanic_id" class="form-control bg-dark text-white border-secondary">
                                    <option value="">-- Biarkan Kosong (Pilih Nanti) --</option>
                                    @foreach($mechanics as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Catatan / Keluhan</label>
                            <textarea name="notes" class="form-control bg-dark text-white border-secondary" rows="2" placeholder="Contoh: Service rutin + ganti oli"></textarea>
                        </div>

                        <div class="form-group mt-3">
                            <label class="d-block mb-2 font-weight-bold">Pilih Jasa Service:</label>
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
                    </div>

                    <div class="p-4 rounded mb-4 border border-info" style="background-color: #0f172a;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-info font-weight-bold m-0"><i class="fas fa-cogs mr-2"></i>Penggunaan Sparepart (Opsional)</h6>
                            <button type="button" class="btn btn-sm btn-success" onclick="addSparepartRow()">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-dark" id="sparepartTable" style="background-color: #1e293b;">
                                <thead>
                                    <tr>
                                        <th width="50%">Nama Barang</th>
                                        <th width="20%">Qty</th>
                                        <th width="20%">Estimasi Harga</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    </tbody>
                            </table>
                            <div id="emptyRowMessage" class="text-center text-muted p-2">
                                Belum ada sparepart yang dipilih. Klik tombol "Tambah Barang" jika ada penggantian part.
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold text-uppercase">
                        <i class="fas fa-save mr-2"></i> Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- DATA DARI CONTROLLER UNTUK JS --}}
<script>
    // Ambil data sparepart dari controller
    const sparepartsData = @json($spareParts);
</script>
@endsection

@push('scripts')
<script>
    // --- 1. LOGIC TAB TOGGLE (BAWAAN KAMU) ---
    function toggleCustomerType(type) {
        if(type === 'existing') {
            document.getElementById('section-existing').classList.remove('d-none');
            document.getElementById('section-walkin').classList.add('d-none');
        } else {
            document.getElementById('section-existing').classList.add('d-none');
            document.getElementById('section-walkin').classList.remove('d-none');
        }
    }

    // --- 2. LOGIC FILTER MOBIL SESUAI CUSTOMER (BARU) ---
    document.getElementById('selectCustomer').addEventListener('change', function() {
        const selectedUserId = this.value;
        const vehicleSelect = document.getElementById('selectVehicle');
        const vehicleOptions = document.querySelectorAll('.vehicle-option');
        
        // Reset pilihan
        vehicleSelect.value = "";
        
        // Filter opsi
        let found = 0;
        vehicleOptions.forEach(option => {
            if (selectedUserId === "" || option.getAttribute('data-user') === selectedUserId) {
                option.style.display = 'block'; // Munculkan
                found++;
            } else {
                option.style.display = 'none';  // Sembunyikan
            }
        });

        if(found === 0) {
            // Opsional: Alert jika user tidak punya mobil
            // alert("Pelanggan ini belum mendaftarkan kendaraan.");
        }
    });

    // --- 3. LOGIC TABLE SPAREPART DINAMIS (BARU) ---
    let rowIdx = 0;

    function addSparepartRow() {
        document.getElementById('emptyRowMessage').style.display = 'none';

        // Buat Dropdown Option dari data sparepart
        let options = '<option value="">-- Pilih Barang --</option>';
        sparepartsData.forEach(part => {
            // Format Rupiah sederhana
            let priceFmt = new Intl.NumberFormat('id-ID').format(part.sell_price);
            options += `<option value="${part.id}" data-price="${part.sell_price}">
                            ${part.name} (Stok: ${part.stock}) - Rp ${priceFmt}
                        </option>`;
        });

        // Template baris tabel (sesuaikan class dengan tema gelap kamu)
        const html = `
            <tr id="row${rowIdx}">
                <td>
                    <select name="spare_parts[${rowIdx}][id]" class="form-control bg-dark text-white border-secondary part-select" required onchange="updatePrice(${rowIdx})">
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" name="spare_parts[${rowIdx}][qty]" class="form-control bg-dark text-white border-secondary" value="1" min="1" required>
                </td>
                <td>
                    <input type="text" class="form-control bg-dark text-white border-secondary price-display" id="price${rowIdx}" readonly value="0">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowIdx})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        document.querySelector('#sparepartTable tbody').insertAdjacentHTML('beforeend', html);
        rowIdx++;
    }

    function removeRow(id) {
        document.getElementById(`row${id}`).remove();
        const tbody = document.querySelector('#sparepartTable tbody');
        if (tbody.children.length === 0) {
            document.getElementById('emptyRowMessage').style.display = 'block';
        }
    }

    function updatePrice(id) {
        const select = document.querySelector(`#row${id} .part-select`);
        const price = select.options[select.selectedIndex].getAttribute('data-price') || 0;
        document.getElementById(`price${id}`).value = new Intl.NumberFormat('id-ID').format(price);
    }
</script>
@endpush