<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $pembeliUsers = User::where('role', 'pembeli')->latest()->paginate(10, ['*'], 'pembeli_page');
        $kurirUsers   = User::where('role', 'kurir')->latest()->paginate(10, ['*'], 'kurir_page');
        $totalPembeli = User::where('role', 'pembeli')->count();
        $totalKurir   = User::where('role', 'kurir')->count();

        return view('admin.users.index', compact('pembeliUsers', 'kurirUsers', 'totalPembeli', 'totalKurir'));
    }

    public function createKurir()
    {
        return view('admin.users.create-kurir');
    }

    public function storeKurir(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'no_telp'  => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'no_telp'  => $request->no_telp,
            'password' => bcrypt($request->password),
            'role'     => 'kurir',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun kurir berhasil dibuat!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Akun admin tidak bisa dihapus!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}
