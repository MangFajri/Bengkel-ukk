<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Requests\Customer\StoreVehicleRequest;
use App\Http\Requests\Customer\UpdateVehicleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('user_id', Auth::id())->latest()->paginate(10);
        return view('customer.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('customer.vehicles.create');
    }

    public function store(StoreVehicleRequest $request)
    {
        $validatedData = $request->validated();
        // Secara otomatis set pemilik kendaraan adalah user yang sedang login
        $validatedData['user_id'] = Auth::id();

        Vehicle::create($validatedData);

        return redirect()->route('customer.vehicles.index')->with('success', 'Kendaraan baru berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle)
    {
        // KEAMANAN: Pastikan pelanggan hanya bisa mengedit kendaraannya sendiri
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        return view('customer.vehicles.edit', compact('vehicle'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        // KEAMANAN: Pastikan pelanggan hanya bisa mengupdate kendaraannya sendiri
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        
        $vehicle->update($request->validated());

        return redirect()->route('customer.vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        // KEAMANAN: Pastikan pelanggan hanya bisa menghapus kendaraannya sendiri
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $vehicle->delete();
            return redirect()->route('customer.vehicles.index')->with('success', 'Data kendaraan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('customer.vehicles.index')->with('error', 'Kendaraan tidak bisa dihapus karena memiliki riwayat servis.');
        }
    }
}