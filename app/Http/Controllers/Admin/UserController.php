<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua user kecuali yang sedang login
        $users = User::where('id', '!=', Auth::id())->latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
       $validatedData = $request->validated();
        
        // --- LOGIKA TAMBAHAN (SOLUSI SOFT DELETE) ---
        // Cek apakah ada user 'sampah' (soft deleted) dengan email ini?
        $trashedUser = User::onlyTrashed()->where('email', $request->email)->first();

        if ($trashedUser) {
            // Hapus permanen data lama agar emailnya bisa dipakai user baru ini
            $trashedUser->forceDelete();
        }
        // --------------------------------------------

        // Handle checkbox
        $validatedData['is_active'] = $request->has('is_active');
        
        // Hashing password sebelum disimpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Buat user baru
        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:admin,mechanic,customer',
        // Password opsional, hanya divalidasi jika diisi
        'password' => 'nullable|string|min:8', 
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
    ];

    // Logic Reset Password
    if ($request->filled('password')) {
        $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('admin.users.index')
        ->with('success', 'Data pengguna berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
       // Melakukan soft delete
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}