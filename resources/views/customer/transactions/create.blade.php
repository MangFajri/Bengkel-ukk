@extends('layouts.customer')

@section('title', 'Booking Service')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-white font-weight-bold">Formulir Booking</h1>
                <a href="{{ route('customer.transactions.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-primary">Isi Data Service</h6>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger border-0 mb-4" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Gagal!</strong> {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success border-0 mb-4" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('customer.transactions.store') }}" method="POST"></form>
                    <form action="{{ route('customer.transactions.store') }}" method="POST">
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

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="text-gray-400 small font-weight-bold text-uppercase">Pilih Kendaraan</label>
                                <select name="vehicle_id" class="form-control text-white"
                                    style="background-color: #0f172a; border: 1px solid #334155;" required>
                                    <option value="">-- Pilih Mobil Anda --</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">
                                            {{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->plate_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-white font-weight-bold">Rencana Kedatangan (Tanggal & Jam)</label>
                                {{-- type="datetime-local" memungkinkan user memilih Tgl + Jam sekaligus --}}
                                <input type="datetime-local" name="booking_date"
                                    class="form-control bg-dark text-white border-secondary" min="{{ date('Y-m-d\TH:i') }}"
                                    {{-- Tidak boleh pilih masa lalu --}} required>
                                <small class="text-muted">Pilih jam operasional (08:00 - 16:00)</small>
                            </div>
                        </div>

                        <hr class="border-secondary my-4" style="border-color: #334155 !important;">

                        <div class="form-group">
                            <label class="text-gray-400 small font-weight-bold text-uppercase mb-3">Pilih Layanan
                                Service</label>
                            <div class="row">
                                @foreach($services as $service)
                                    <div class="col-md-6 mb-2">
                                        <div class="custom-control custom-checkbox p-3 rounded"
                                            style="background-color: #0f172a; border: 1px solid #334155;">
                                            <input type="checkbox" class="custom-control-input service-checkbox"
                                                id="service_{{ $service->id }}" name="service_ids[]" value="{{ $service->id }}"
                                                data-price="{{ $service->price }}">
                                            <label
                                                class="custom-control-label d-flex justify-content-between w-100 align-items-center"
                                                for="service_{{ $service->id }}">
                                                <span class="text-white font-weight-bold">{{ $service->name }}</span>
                                                <span class="badge badge-success">Rp
                                                    {{ number_format($service->price, 0, ',', '.') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="alert alert-info d-flex justify-content-between align-items-center mt-3"
                            style="background-color: rgba(54, 185, 204, 0.1); border: 1px solid #36b9cc; color: #36b9cc;">
                            <span class="font-weight-bold"><i class="fas fa-calculator mr-2"></i>Estimasi Biaya:</span>
                            <span class="h4 mb-0 font-weight-bold" id="total-price">Rp 0</span>
                        </div>

                        <div class="form-group mt-4">
                            <label class="text-gray-400 small font-weight-bold text-uppercase">Keluhan / Catatan</label>
                            <textarea name="notes" rows="3" class="form-control text-white"
                                placeholder="Contoh: Rem agak bunyi, tolong sekalian cek AC..."
                                style="background-color: #0f172a; border: 1px solid #334155;"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold mt-4">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Booking Sekarang
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Script Sederhana untuk Hitung Total Real-time
            document.addEventListener('DOMContentLoaded', function () {
                const checkboxes = document.querySelectorAll('.service-checkbox');
                const totalLabel = document.getElementById('total-price');

                function calculateTotal() {
                    let total = 0;
                    checkboxes.forEach(box => {
                        if (box.checked) {
                            total += parseFloat(box.getAttribute('data-price'));
                        }
                    });
                    // Format Rupiah
                    totalLabel.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                }

                checkboxes.forEach(box => {
                    box.addEventListener('change', calculateTotal);
                });
            });
        </script>
    @endpush
@endsection