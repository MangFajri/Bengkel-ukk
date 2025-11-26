<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Menampilkan daftar kendaraan milik customer yang sedang login.
     */
    public function index()
    {
        // Ambil kendaraan HANYA milik user yang sedang login
        $vehicles = Vehicle::where('user_id', Auth::id())->latest()->get();
        
        return view('customer.vehicles.index', compact('vehicles'));
    }

    /**
     * Menampilkan form tambah kendaraan.
     */
    public function create()
    {
        return view('customer.vehicles.create');
    }

    /**
     * Menyimpan data kendaraan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plate_number'   => 'required|string|max:20|unique:vehicles',
            'brand'          => 'required|string|max:50',
            'model'          => 'required|string|max:50',
            'year'           => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color'          => 'nullable|string|max:30',
            // Tambahan Validasi (Boleh kosong/nullable biar user gak males isi)
            'engine_number'  => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:100',
        ]);

        Auth::user()->vehicles()->create([
            'plate_number'   => $request->plate_number,
            'brand'          => $request->brand,
            'model'          => $request->model,
            'year'           => $request->year,
            'color'          => $request->color,
            // Simpan data baru
            'engine_number'  => $request->engine_number,
            'chassis_number' => $request->chassis_number,
        ]);

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan!');
    }

    /**
     * (Opsional untuk UKK) Form Edit
     */
    public function edit(Vehicle $vehicle)
    {
        // Pastikan mobil ini punya dia
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        return view('customer.vehicles.edit', compact('vehicle'));
    }

    /**
     * (Opsional untuk UKK) Update Data
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicle->id,
            'brand'         => 'required|string|max:50',
            'model'         => 'required|string|max:50',
            'year'          => 'required|integer',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('customer.vehicles.index')->with('success', 'Data kendaraan diperbarui.');
    }

    /**
     * Hapus Kendaraan
     */
    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        
        $vehicle->delete();
        return redirect()->back()->with('success', 'Kendaraan dihapus.');
    }
}