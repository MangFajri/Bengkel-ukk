<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('user_id', Auth::id())->latest()->get();
        return view('customer.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('customer.vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            // Perbaikan: Pastikan unique cek ke tabel vehicles kolom plate_number
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
            // Pastikan field ini ada di database jika mau dipakai
            'engine_number' => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:100',
        ]);

        Vehicle::create([
            'user_id' => Auth::id(),
            'brand' => $request->brand,
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'year' => $request->year,
            'color' => $request->color,
            // Hapus dua baris bawah ini jika database belum di-migrate
            'engine_number' => $request->engine_number,
            'chassis_number' => $request->chassis_number,
        ]);

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan!');
    }

    public function edit(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        return view('customer.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        // MASALAH UTAMA KAMU DISINI SEBELUMNYA (license_plate vs plate_number)
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number,' . $vehicle->id,
            'year' => 'required|integer',
            'color' => 'nullable|string|max:50',
        ]);

        // Update data aman
        $vehicle->update($request->only([
            'brand', 'model', 'plate_number', 'year', 'color', 
            'engine_number', 'chassis_number'
        ]));

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Data kendaraan diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        
        $vehicle->delete();
        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Kendaraan dihapus.');
    }
}