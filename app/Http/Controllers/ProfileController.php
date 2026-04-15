<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $provinces = \Laravolt\Indonesia\Models\Province::all();

        return view('profile.edit', [
            'user'      => $request->user(),
            'provinces' => $provinces,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'string', 'max:15'],
        ]);

        $user = $request->user();

        $user->update([
            'name'    => $request->name,
            'no_telp' => $request->no_telp,
        ]);

        if ($user->role === 'admin' && $user->store) {
            $user->store->update([
                'province_id' => $user->store->province_id,
            ]);
        }

        return redirect()->route('profile.edit')->with('success', 'Profil lu udah aman, Cu!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // ProfileController.php

public function updateStore(Request $request)
{
    $request->validate([
        'nama_toko'   => 'required|string|max:255',
        'deskripsi'   => 'nullable|string',
        'province_id' => 'required',
        'city_id'     => 'required',
        'district_id' => 'required',
        'village_id'  => 'required',
        'alamat'      => 'required|string|max:500',
    ]);

    $store = auth()->user()->store;

    if (!$store) {
        return back()->with('error', 'Toko tidak ditemukan.');
    }

    $store->update([
        'nama_toko'   => $request->nama_toko,
        'deskripsi'   => $request->deskripsi,
        'province_id' => $request->province_id,
        'city_id'     => $request->city_id,
        'district_id' => $request->district_id,
        'village_id'  => $request->village_id,
        'alamat'      => $request->alamat,
    ]);

    return back()->with('success', 'Informasi toko berhasil diperbarui!');
}
}
