<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
class ServiceController extends Controller
{
     /**
     * Menampilkan daftar semua Jasa (Services).
     */
    public function index()
    {
        // Mengambil semua data jasa, diurutkan dari yang terbaru, dan paginasi 10 item per halaman
        $services = Service::latest()->paginate(10);
        
        // Mengirim data ke view
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya menampilkan view
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        // Mengambil data yang sudah divalidasi oleh StoreServiceRequest
        $validatedData = $request->validated();
        
        // Menangani checkbox 'is_active'
        // Jika checkbox tidak dicentang, nilainya tidak akan dikirim. Kita set default ke 0 (false).
        $validatedData['is_active'] = $request->has('is_active');

        // Membuat record baru di database
        Service::create($validatedData);

        // Redirect kembali ke halaman daftar jasa dengan pesan sukses
        return redirect()->route('admin.services.index')->with('success', 'Data Jasa berhasil ditambahkan.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

     /**
     * Show the form for editing the specified resource.
     * Laravel akan otomatis mencari Service berdasarkan ID di URL.
     */
    public function edit(Service $service)
    {
        // Mengirim data service yang mau diedit ke view
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        // Mengambil data yang sudah divalidasi
        $validatedData = $request->validated();

        // Menangani checkbox 'is_active'
        $validatedData['is_active'] = $request->has('is_active');

        // Update data di database
        $service->update($validatedData);

        // Redirect kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.services.index')->with('success', 'Data Jasa berhasil diperbarui.');
    }

     /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Melakukan soft delete (karena model Service menggunakan trait SoftDeletes)
        $service->delete();

        // Redirect kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.services.index')->with('success', 'Data Jasa berhasil dihapus.');
    }
}
