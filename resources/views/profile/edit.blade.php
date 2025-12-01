<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-white leading-tight tracking-wide">
                <span class="text-blue-500">MY</span> ACCOUNT
            </h2>
            <span class="text-xs text-gray-500 uppercase tracking-widest">User Settings</span>
        </div>
    </x-slot>

    {{-- STYLE INJECTION: CUSTOM CSS UNTUK PROFIL GANTENG --}}
    <style>
        /* Background Utama dengan pola halus */
        .min-h-screen {
            background-color: #0f172a !important;
            background-image: radial-gradient(#1e293b 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(30, 41, 59, 0.7); /* #1e293b dengan opacity */
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        /* Gradient Cover untuk Kartu Kiri */
        .profile-cover {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            height: 120px;
            width: 100%;
            border-radius: 0.5rem 0.5rem 0 0;
            position: relative;
        }

        /* Input Field Ganteng */
        .cyber-input input {
            background-color: #0f172a !important;
            border: 1px solid #334155 !important;
            color: #e2e8f0 !important;
            padding-left: 2.5rem !important; /* Kasih ruang buat ikon */
            transition: all 0.3s ease;
        }
        .cyber-input input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3) !important;
        }
        
        /* Posisi Ikon di dalam Input */
        .input-icon-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 10px;
            top: 10px; /* Sesuaikan dengan tinggi input */
            color: #64748b;
            pointer-events: none;
        }
        
        /* Label Styling */
        .form-label {
            color: #94a3b8 !important;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Section Title Style */
        .section-title {
            position: relative;
            padding-left: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #3b82f6;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: Identity Card --}}
                <div class="lg:col-span-1">
                    <div class="glass-card rounded-lg shadow-xl relative">
                        {{-- Cover Photo Gradient --}}
                        <div class="profile-cover"></div>
                        
                        <div class="px-6 pb-6">
                            {{-- Avatar (Half inside cover, half outside) --}}
                            <div class="relative -mt-16 mb-4 text-center">
                                <div class="mx-auto w-32 h-32 rounded-full bg-gray-900 border-4 border-[#0f172a] flex items-center justify-center shadow-lg overflow-hidden">
                                    {{-- Inisial Nama --}}
                                    <span class="text-4xl font-extrabold text-white tracking-widest">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                            </div>

                            {{-- User Info --}}
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                                <p class="text-sm text-blue-400 font-mono">{{ $user->email }}</p>
                                
                                {{-- Role Badge --}}
                                <div class="mt-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest
                                        {{ $user->role === 'admin' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : '' }}
                                        {{ $user->role === 'mechanic' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : '' }}
                                        {{ $user->role === 'customer' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : '' }}">
                                        <span class="w-2 h-2 mr-2 rounded-full bg-current animate-pulse"></span>
                                        {{ $user->role }}
                                    </span>
                                </div>
                            </div>

                            {{-- Stats Grid --}}
                            <div class="grid grid-cols-2 gap-4 border-t border-gray-700 pt-6">
                                <div class="text-center">
                                    <span class="block text-xs text-gray-500 uppercase">Joined</span>
                                    <span class="block text-sm font-bold text-gray-300">{{ $user->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="text-center border-l border-gray-700">
                                    <span class="block text-xs text-gray-500 uppercase">Status</span>
                                    <span class="block text-sm font-bold text-green-400">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shortcut Card (Opsional) --}}
                    <div class="glass-card rounded-lg shadow-xl mt-6 p-6">
                         <h4 class="text-sm font-bold text-gray-400 uppercase mb-4">Quick Actions</h4>
                         <button onclick="window.history.back()" class="w-full flex items-center justify-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition text-sm">
                            ← Kembali ke Dashboard
                         </button>
                    </div>
                </div>

                {{-- KOLOM KANAN: Form Settings --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- 1. EDIT PROFILE --}}
                    <div class="glass-card rounded-lg p-6 sm:p-8">
                        <div class="section-title">
                            <h3 class="text-lg font-bold text-white">Edit Informasi</h3>
                            <p class="text-sm text-gray-400">Update nama profil dan alamat email akun Anda.</p>
                        </div>
                        
                        <div class="cyber-input space-y-4 max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    {{-- 2. CHANGE PASSWORD --}}
                    <div class="glass-card rounded-lg p-6 sm:p-8">
                         <div class="section-title border-yellow-500">
                            <h3 class="text-lg font-bold text-white">Keamanan / Password</h3>
                            <p class="text-sm text-gray-400">Pastikan akun Anda menggunakan password yang kuat.</p>
                        </div>
                        
                        <div class="cyber-input space-y-4 max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    {{-- 3. DELETE ACCOUNT --}}
                    <div class="glass-card rounded-lg p-6 sm:p-8 border-l-4 border-red-600 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-red-600 blur-3xl opacity-20 rounded-full pointer-events-none"></div>
                        
                        <div class="relative z-10">
                             <h3 class="text-lg font-bold text-red-400 mb-2">⚠ Zona Berbahaya</h3>
                             <div class="max-w-xl text-gray-300 text-sm mb-4">
                                 Menghapus akun bersifat permanen. Semua data history transaksi dan kendaraan akan hilang.
                             </div>
                             @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT: SUNTIK ICONS KE FORM BAWAAN LARAVEL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi Helper untuk ngebungkus input dengan ikon
            function addIconToInput(inputId, svgIcon) {
                const input = document.getElementById(inputId);
                if(input) {
                    // Buat wrapper
                    const wrapper = document.createElement('div');
                    wrapper.className = 'input-icon-wrapper';
                    
                    // Insert wrapper sebelum input
                    input.parentNode.insertBefore(wrapper, input);
                    
                    // Pindahkan input ke dalam wrapper
                    wrapper.appendChild(input);
                    
                    // Tambah ikon
                    const iconDiv = document.createElement('div');
                    iconDiv.className = 'input-icon';
                    iconDiv.innerHTML = svgIcon;
                    wrapper.appendChild(iconDiv);

                    // Paksa label jadi gaya kita
                    const label = wrapper.parentNode.querySelector('label');
                    if(label) label.classList.add('form-label');
                }
            }

            // SVG Icons (Simple paths)
            const userIcon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>';
            const emailIcon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>';
            const lockIcon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>';

            // Terapkan ke ID input bawaan Breeze
            addIconToInput('name', userIcon);
            addIconToInput('email', emailIcon);
            addIconToInput('current_password', lockIcon);
            addIconToInput('password', lockIcon);
            addIconToInput('password_confirmation', lockIcon);
        });
    </script>
</x-app-layout>