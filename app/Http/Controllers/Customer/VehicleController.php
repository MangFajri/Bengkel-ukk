<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function index()
{
    // LOGIC PRO: Eager Loading "is_being_serviced"
    // Kita minta database: "Ambil mobil user ini, SEKALIAN cek ada gak transaksi yang statusnya < 4?"
    // Hasilnya nanti berupa boolean true/false di atribut 'is_being_serviced'
    
    $vehicles = Vehicle::where('user_id', Auth::id())
        ->withExists(['transactions as is_being_serviced' => function ($query) {
            $query->where('service_status_id', '<', 4); // Filter status aktif
        }])
        ->latest()
        ->get();

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
            // FIX LOGIKA: Abaikan yang sudah dihapus (Soft Deletes)
            'plate_number' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('vehicles')->whereNull('deleted_at')
            ],
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:50',
        ]);

        Vehicle::create([
            'user_id' => Auth::id(),
            'brand' => $request->brand,
            'model' => $request->model,
            'plate_number' => strtoupper($request->plate_number), // Paksa Uppercase biar rapi
            'year' => $request->year,
            'color' => $request->color,
            // Field optional jika ada di form
            'engine_number' => $request->engine_number ?? null, 
            'chassis_number' => $request->chassis_number ?? null,
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

        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            // FIX LOGIKA UPDATE: 
            // 1. Ignore ID mobil ini sendiri (biar gak error kalau gak ganti plat)
            // 2. Abaikan sampah (deleted_at)
            'plate_number' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('vehicles')->ignore($vehicle->id)->whereNull('deleted_at')
            ],
            'year' => 'required|integer',
            'color' => 'nullable|string|max:50',
        ]);

        $vehicle->update([
            'brand' => $request->brand,
            'model' => $request->model,
            'plate_number' => strtoupper($request->plate_number),
            'year' => $request->year,
            'color' => $request->color,
        ]);

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