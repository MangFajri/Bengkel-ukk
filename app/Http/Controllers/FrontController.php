<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\SparePart;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    // Halaman Utama (Landing Page)
    public function index()
    {
        // Ambil 3 Service unggulan untuk ditampilkan di depan
        $services = Service::where('is_active', true)->take(3)->get();

        return view('welcome', compact('services'));
    }

    // Halaman Katalog Sparepart (Tujuan tombol "Cek Katalog")
    public function catalog(Request $request)
    {
        // Fitur pencarian sederhana
        $query = SparePart::where('is_active', true)->where('stock', '>', 0);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $spareParts = $query->paginate(12); // 12 barang per halaman

        return view('catalog', compact('spareParts'));
    }
}