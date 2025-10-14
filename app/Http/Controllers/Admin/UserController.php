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
        
        // Handle checkbox
        $validatedData['is_active'] = $request->has('is_active');
        
        // Hashing password sebelum disimpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Buat user baru
        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // mengirim data user yang mau diedit ke view edit
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $validatedData['is_active'] = $request->has('is_active');

        // Cek apakah admin mengisi password baru
        if ($request->filled('password')) {
            // Jika diisi, hash password baru
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            // Jika tidak diisi, hapus password dari array agar tidak meng-update password lama
            unset($validatedData['password']);
        }

        // Update data di database
        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Data Pengguna berhasil diperbarui.');
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
