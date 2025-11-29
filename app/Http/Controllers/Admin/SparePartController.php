<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use App\Http\Requests\StoreSparePartRequest;
use App\Http\Requests\UpdateSparePartRequest;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data sparepart, diurutkan dari yang terbaru, paginasi 10 item per halaman
        $spareParts = SparePart::latest()->paginate(10);
        
        // Mengirim data ke view
        return view('admin.spare-parts.index', compact('spareParts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.spare-parts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSparePartRequest $request)
    {
        $validatedData = $request->validated();
        
        // === [START PERUBAHAN] ===
        // Cek apakah ada sparepart 'sampah' (soft deleted) dengan SKU ini?
        $trashedPart = SparePart::onlyTrashed()->where('sku', $request->sku)->first();

        if ($trashedPart) {
            // Hapus permanen data lama agar SKU-nya bisa dipakai sparepart baru ini
            $trashedPart->forceDelete();
        }
        // === [END PERUBAHAN] ===

        $validatedData['is_active'] = $request->has('is_active');

        SparePart::create($validatedData);

        return redirect()->route('admin.spare-parts.index')->with('success', 'Data Sparepart berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SparePart $sparePart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SparePart $sparePart)
    {
        // Mengirim data sparepart yang mau di edit ke view
        return view('admin.spare-parts.edit', compact('sparePart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSparePartRequest $request, SparePart $sparePart)
    {
        $validatedData = $request->validated();
        $validatedData['is_active'] = $request->has('is_active');

        // Update data di database
        $sparePart->update($validatedData);

        // Redirect kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.spare-parts.index')->with('success', 'Data Sparepart berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SparePart $sparePart)
    {
        // Melakukan soft delete
        $sparePart->delete();

        // Redirect kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.spare-parts.index')->with('success', 'Data Sparepart berhasil dihapus.');
    }
}