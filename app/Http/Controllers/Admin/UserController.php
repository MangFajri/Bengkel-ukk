<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Pesan Error Kustom (Bahasa Indonesia)
     * Biar user gak bingung kalau kena validasi.
     */
    private $customMessages = [
        'name.required' => 'Nama wajib diisi.',
        'name.regex' => 'Nama hanya boleh berisi huruf, spasi, dan titik.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email ini sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'phone.required' => 'Nomor telepon wajib diisi.',
        'phone.numeric' => 'Nomor telepon harus berupa angka (0-9).',
        'phone.digits_between' => 'Nomor telepon tidak valid (harus 10-14 digit).',
        'address.required' => 'Alamat wajib diisi.',
        'address.min' => 'Alamat terlalu pendek, mohon isi alamat lengkap (min 10 karakter).',
    ];

    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // 1. VALIDASI KETAT (Strict Mode)
        $request->validate([
            // Nama: Huruf, spasi, titik. Min 3 huruf.
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s\.]+$/'], 
            
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,mechanic,customer',
            
            // HP: Wajib Angka, 10-14 digit (Standar Indo)
            'phone' => 'required|numeric|digits_between:10,14', 
            
            // Alamat: Minimal 10 karakter biar ga cuma "Sby"
            'address' => 'required|string|min:10|max:500', 
        ], $this->customMessages);

        // 2. LOGIC SOFT DELETE (Cek Tong Sampah)
        $trashedUser = User::onlyTrashed()->where('email', $request->email)->first();

        if ($trashedUser) {
            // Kalau ada "mayat" user dengan email ini, hapus permanen biar bisa daftar baru
            $trashedUser->forceDelete();
        } else {
            // Kalau bersih, pastikan unique normal
            $request->validate(['email' => 'unique:users'], $this->customMessages);
        }

        // 3. SIMPAN
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // VALIDASI UPDATE (Password Nullable)
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z\s\.]+$/'],
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,mechanic,customer',
            'password' => 'nullable|string|min:8', 
            'phone' => 'required|numeric|digits_between:10,14', // Update juga harus ketat
            'address' => 'required|string|min:10|max:500',
        ], $this->customMessages);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}