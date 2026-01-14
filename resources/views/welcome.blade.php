<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Bengkel UKK') }}</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    {{-- Tailwind CSS (CDN untuk Landing Page Only) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Oswald', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Animasi Scroll Reveal */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.5, 0, 0, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* Marquee */
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-marquee { animation: marquee 20s linear infinite; }
        
        /* Stroke Text Effect */
        .stroke-text {
            -webkit-text-stroke: 1px #334155;
            color: transparent;
        }
    </style>
</head>

<body class="bg-slate-950 text-slate-200 antialiased selection:bg-orange-500 selection:text-white overflow-x-hidden">

    {{-- NAVBAR --}}
    <nav class="fixed w-full top-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold tracking-tighter font-display uppercase text-white hover:text-orange-500 transition">
                Fajri<span class="text-orange-500">Garage.</span>
            </a>
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-white hover:text-orange-500 transition font-display uppercase tracking-wider text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-bold text-white uppercase tracking-widest border border-slate-600 hover:border-orange-500 hover:text-orange-500 transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold text-slate-900 bg-orange-600 uppercase tracking-widest border border-orange-600 hover:bg-orange-700 hover:border-orange-700 transition">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 border-b border-slate-800 overflow-hidden">
        <div class="absolute top-0 right-0 -z-10 opacity-20">
            <svg width="800" height="800" viewBox="0 0 100 100"><circle cx="100" cy="0" r="50" fill="#f97316" filter="blur(40px)" /></svg>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="col-span-12 lg:col-span-7 reveal">
                <div class="flex items-center gap-2 mb-6">
                    <span class="h-px w-12 bg-orange-500"></span>
                    <span class="text-orange-500 uppercase tracking-widest text-sm font-bold">Since 2025</span>
                </div>
                <h1 class="text-7xl lg:text-9xl font-bold uppercase leading-none tracking-tight text-white mb-8">
                    Master <br><span class="stroke-text text-slate-800">Mechanic.</span>
                </h1>
                <p class="text-slate-400 text-lg max-w-lg mb-10 leading-relaxed">
                    Mitra kesehatan mobil Anda. Dari ganti oli hingga turun mesin, kami tangani dengan presisi teknologi terkini.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('login') }}" class="bg-orange-600 text-white px-8 py-4 text-lg font-display uppercase tracking-wider hover:bg-orange-700 transition duration-300 shadow-lg shadow-orange-900/20">
                        Booking Service
                    </a>
                    <a href="#services" class="border border-slate-700 text-slate-300 px-8 py-4 text-lg font-display uppercase tracking-wider hover:border-white hover:text-white transition duration-300">
                        Lihat Layanan
                    </a>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-5 relative reveal delay-200">
                <div class="relative z-10 border-2 border-slate-800 p-2 bg-slate-900/50 backdrop-blur-sm rotate-3 hover:rotate-0 transition duration-500">
                    {{-- Ganti gambar mobil keren --}}
                    <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1000&auto=format&fit=crop" alt="Sports Car" class="w-full h-auto grayscale hover:grayscale-0 transition duration-700">
                </div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-600/20 rounded-full blur-3xl"></div>
            </div>
        </div>
    </header>

    {{-- RUNNING TEXT --}}
    <div class="bg-orange-600 text-slate-950 py-4 overflow-hidden whitespace-nowrap border-y border-orange-700 rotate-1 origin-left scale-105 shadow-xl relative z-20">
        <div class="inline-block animate-marquee text-2xl font-bold font-display uppercase tracking-widest">
            GANTI OLI • TUNE UP • OVERHAUL • CAT BODY • KAKI-KAKI • REMAPPING ECU • SPAREPART ORIGINAL • GARANSI RESMI • LAYANAN DARURAT •
            GANTI OLI • TUNE UP • OVERHAUL • CAT BODY • KAKI-KAKI • REMAPPING ECU • SPAREPART ORIGINAL • GARANSI RESMI • LAYANAN DARURAT •
        </div>
    </div>

    {{-- STATS SECTION --}}
    <section class="py-24 bg-slate-950 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
                <div class="reveal">
                    <h3 class="text-5xl font-display font-bold text-white mb-2">100%</h3>
                    <p class="text-slate-500 uppercase tracking-widest text-sm">Kepuasan Pelanggan</p>
                </div>
                <div class="reveal delay-100">
                    <h3 class="text-5xl font-display font-bold text-white mb-2">24/7</h3>
                    <p class="text-slate-500 uppercase tracking-widest text-sm">Booking Online</p>
                </div>
                <div class="reveal delay-200">
                    <h3 class="text-5xl font-display font-bold text-white mb-2">Pro</h3>
                    <p class="text-slate-500 uppercase tracking-widest text-sm">Mekanik Bersertifikat</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICES SECTION (DYNAMIC LOOP) --}}
    <section id="services" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16 text-center reveal">
                <span class="text-orange-500 font-bold tracking-widest uppercase text-sm">Layanan Unggulan</span>
                <h2 class="text-5xl md:text-6xl font-display uppercase text-white mt-4">Solusi <span class="text-slate-700">Total.</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                
                {{-- 1. LOOP DATA DARI DATABASE --}}
                @if(isset($services) && count($services) > 0)
                    @foreach($services as $index => $service)
                        <div class="col-span-12 {{ $index == 0 ? 'md:col-span-8' : 'md:col-span-4' }} bg-slate-900 border border-slate-800 p-8 group hover:border-orange-500/50 transition duration-500 reveal">
                            <div class="h-full flex flex-col justify-between">
                                <div class="mb-8">
                                    {{-- Icon dinamis berdasarkan index --}}
                                    <i class="fas fa-{{ $index == 0 ? 'cogs' : ($index == 1 ? 'oil-can' : 'tools') }} text-4xl text-slate-600 group-hover:text-orange-500 transition duration-300 mb-4"></i>
                                    
                                    <h3 class="text-3xl font-display text-white uppercase mb-2">{{ $service->name }}</h3>
                                    <p class="text-slate-400 max-w-md">{{ Str::limit($service->description ?? 'Layanan terbaik untuk performa maksimal kendaraan Anda.', 100) }}</p>
                                </div>
                                <div class="flex justify-between items-end">
                                    <span class="text-2xl font-bold text-white">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                    <a href="{{ route('login') }}" class="w-12 h-12 bg-slate-800 flex items-center justify-center rounded-full group-hover:bg-orange-600 group-hover:text-white transition">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback jika database kosong --}}
                    <div class="col-span-12 text-center text-slate-500 py-12 border border-slate-800 border-dashed">
                        <p>Belum ada layanan yang ditampilkan.</p>
                    </div>
                @endif

                {{-- 2. BANNER CEK KATALOG --}}
                <div class="col-span-12 md:col-span-12 bg-orange-600 p-8 flex flex-col md:flex-row items-center justify-between gap-6 reveal delay-100 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-3xl font-display text-white uppercase mb-2">Butuh Sparepart Asli?</h3>
                        <p class="text-orange-100">Kami menyediakan suku cadang original bergaransi. Cek stok kami sekarang.</p>
                    </div>
                    <a href="{{ route('catalog') }}" class="relative z-10 bg-black text-white px-6 py-3 uppercase font-bold hover:bg-slate-900 transition whitespace-nowrap">
                        Cek Katalog →
                    </a>
                    <i class="fas fa-car-battery absolute -right-6 -bottom-6 text-9xl text-orange-700 opacity-50 rotate-12"></i>
                </div>

            </div>
        </div>
    </section>

    {{-- LOCATION SECTION --}}
    <section class="border-t border-slate-800">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="p-12 lg:p-24 bg-slate-950 flex flex-col justify-center reveal">
                <span class="text-orange-500 font-bold tracking-widest uppercase text-sm mb-4">Lokasi Kami</span>
                <h2 class="text-5xl font-display uppercase text-white mb-8">Datang & <br> Buktikan.</h2>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-slate-900 flex items-center justify-center text-orange-500 border border-slate-800 shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold uppercase">Alamat</h4>
                            <p class="text-slate-400">Jl. Raya Koding No. 404, Sidoarjo (Sebelah SMK)</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-slate-900 flex items-center justify-center text-orange-500 border border-slate-800 shrink-0">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold uppercase">Jam Operasional</h4>
                            <p class="text-slate-400">Senin - Sabtu: 08:00 - 17:00 WIB</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-slate-900 flex items-center justify-center text-orange-500 border border-slate-800 shrink-0">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold uppercase">Kontak & Booking</h4>
                            <p class="text-slate-400 mb-1">+62 812 3456 7890 (Admin)</p>
                            <a href="https://wa.me/6281234567890" target="_blank" class="text-orange-500 text-sm font-bold hover:underline">CHAT WHATSAPP →</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GOOGLE MAPS REAL (Sidoarjo) --}}
            <div class="h-96 lg:h-auto bg-slate-800 relative group">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.2700233591143!2d112.71267467499628!3d-7.435336092575444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e6a00e4d438d%3A0x95e9e03628d508a6!2sAlun-Alun%20Sidoarjo!5e0!3m2!1sen!2sid!4v1709626702435!5m2!1sen!2sid" 
                    width="100%" height="100%" style="border:0; filter: grayscale(100%) invert(92%) contrast(83%);" 
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    class="absolute inset-0 w-full h-full group-hover:filter-none transition duration-700">
                </iframe>
                <div class="absolute inset-0 flex items-center justify-center bg-black/20 pointer-events-none group-hover:opacity-0 transition duration-500">
                    <span class="bg-black text-white px-4 py-2 text-xs uppercase tracking-widest font-bold">Lihat Peta</span>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-950 border-t border-slate-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-end gap-8 mb-12">
            <div>
                <div class="text-3xl font-bold tracking-tighter font-display uppercase text-white mb-4">
                    Fajri<span class="text-orange-500">Garage.</span>
                </div>
                <p class="text-slate-500 max-w-sm">
                    Sistem manajemen bengkel modern untuk efisiensi dan kepuasan pelanggan maksimal.
                </p>
            </div>
            <div class="flex gap-6">
                <a href="#" class="w-10 h-10 rounded-full border border-slate-700 flex items-center justify-center text-slate-400 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition"><i class="fab fa-instagram"></i></a>
                <a href="#" class="w-10 h-10 rounded-full border border-slate-700 flex items-center justify-center text-slate-400 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition"><i class="fab fa-facebook-f"></i></a>
                <a href="https://wa.me/6281234567890" target="_blank" class="w-10 h-10 rounded-full border border-slate-700 flex items-center justify-center text-slate-400 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 border-t border-slate-900 pt-8 flex flex-col md:flex-row justify-between text-sm text-slate-600">
            <p>© 2025 FajriGarage UKK Project. All rights reserved.</p>
            <p>Designed by <span class="text-orange-600">OyFajri</span></p>
        </div>
    </footer>

    {{-- SCROLL ANIMATION SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reveals = document.querySelectorAll('.reveal');
            function checkReveal() {
                const windowHeight = window.innerHeight;
                const elementVisible = 100;
                reveals.forEach((reveal) => {
                    const elementTop = reveal.getBoundingClientRect().top;
                    if (elementTop < windowHeight - elementVisible) {
                        reveal.classList.add('active');
                    }
                });
            }
            window.addEventListener('scroll', checkReveal);
            checkReveal(); // Trigger once on load
        });
    </script>
</body>
</html>