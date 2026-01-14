<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Sparepart - FajriGarage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, .font-display { font-family: 'Oswald', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-200">

    {{-- Navbar Sederhana --}}
    <nav class="fixed w-full top-0 z-50 bg-slate-950/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('landing') }}" class="text-2xl font-bold font-display uppercase text-white">
                Fajri<span class="text-orange-500">Garage.</span>
            </a>
            <a href="{{ route('login') }}" class="text-sm font-bold text-orange-500 border border-orange-500 px-4 py-2 hover:bg-orange-500 hover:text-white transition">
                LOGIN MEMBER
            </a>
        </div>
    </nav>

    <div class="pt-32 pb-12 text-center">
        <h1 class="text-5xl font-bold text-white mb-4 font-display uppercase">Katalog Sparepart</h1>
        <p class="text-slate-400">Suku cadang original kualitas terbaik untuk kendaraan Anda.</p>
        
        {{-- Search Bar --}}
        <form action="{{ route('catalog') }}" method="GET" class="mt-8 max-w-md mx-auto flex gap-2">
            <input type="text" name="search" placeholder="Cari nama barang..." value="{{ request('search') }}" 
                   class="w-full bg-slate-900 border border-slate-700 text-white px-4 py-3 focus:outline-none focus:border-orange-500 transition">
            <button type="submit" class="bg-orange-600 text-white px-6 py-3 font-bold uppercase hover:bg-orange-700 transition">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($spareParts as $part)
                <div class="bg-slate-900 border border-slate-800 group hover:border-orange-500/50 transition duration-300 relative overflow-hidden">
                    <div class="h-48 bg-slate-800 flex items-center justify-center">
                        {{-- Placeholder Gambar (Karena kita belum fitur upload foto barang) --}}
                        <i class="fas fa-box-open text-6xl text-slate-700 group-hover:text-orange-500 transition duration-300"></i>
                    </div>
                    <div class="p-6">
                        <div class="text-xs text-orange-500 font-bold uppercase tracking-widest mb-1">Stok: {{ $part->stock }}</div>
                        <h3 class="text-xl font-bold text-white font-display mb-2 leading-tight">{{ $part->name }}</h3>
                        <p class="text-slate-400 text-sm mb-4 line-clamp-2">Kualitas original, garansi pemasangan di bengkel kami.</p>
                        <div class="flex justify-between items-center border-t border-slate-800 pt-4">
                            <span class="text-lg font-bold text-white">Rp {{ number_format($part->sell_price, 0, ',', '.') }}</span>
                            <span class="text-slate-500 text-sm"><i class="fas fa-tag mr-1"></i> Original</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-search text-4xl text-slate-700 mb-4"></i>
                    <p class="text-slate-500">Barang tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $spareParts->links() }} {{-- Pagination Laravel --}}
        </div>
    </div>

</body>
</html>