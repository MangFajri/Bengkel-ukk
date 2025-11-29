<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\User;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest; 
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data kendaraan beserta data relasi pemiliknya (user)
        $vehicles = Vehicle::with('user')->latest()->paginate(10);
        
        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua user dengan peran 'customer' untuk ditampilkan di dropdown
        $customers = User::where('role', 'customer')->orderBy('name')->get();

        return view('admin.vehicles.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        // === [START LOGIKA SOFT DELETE] ===
        // Cek apakah ada kendaraan 'sampah' (soft deleted) dengan plat nomor ini?
        $trashedVehicle = Vehicle::onlyTrashed()
            ->where('plate_number', $request->plate_number)
            ->first();

        if ($trashedVehicle) {
            // Hapus permanen data lama agar plat nomor bisa dipakai lagi oleh data baru ini
            $trashedVehicle->forceDelete();
        }
        // === [END LOGIKA SOFT DELETE] ===

        Vehicle::create($request->validated());

        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        // Ambil semua user dengan peran 'customer'
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        
        // Kirim data kendaraan dan daftar customer ke view
        return view('admin.vehicles.edit', compact('vehicle', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        // Update data di database dengan data yang sudah tervalidasi
        $vehicle->update($request->validated());

        return redirect()->route('admin.vehicles.index')->with('success', 'Data Kendaraan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        // Karena kita sudah pakai SoftDeletes di Model, $vehicle->delete() ini aman.
        // Dia hanya akan mengisi kolom deleted_at, tidak menghapus baris dari database.
        // Jadi catch QueryException di bawah sebenarnya jarang terjadi kalau soft delete aktif,
        // tapi bagus dipertahankan untuk jaga-jaga (defensive programming).
        
        try {
            $vehicle->delete();
            return redirect()->route('admin.vehicles.index')->with('success', 'Data Kendaraan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Menangkap error jika kendaraan masih terhubung dengan transaksi (jika hard delete)
            return redirect()->route('admin.vehicles.index')->with('error', 'Data Kendaraan tidak bisa dihapus karena masih terhubung dengan data transaksi.');
        }
    }
}