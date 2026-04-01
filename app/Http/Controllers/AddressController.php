<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::id())
            ->with(['province', 'city', 'district', 'village'])
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        return view('profile.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'         => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:100',
            'no_telp'       => 'required|string|max:20',
            'alamat'        => 'required|string',
            'province_id'   => 'required',
            'city_id'       => 'required',
            'district_id'   => 'required',
            'village_id'    => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $isFirst   = UserAddress::where('user_id', Auth::id())->count() === 0;
            $isDefault = $request->boolean('is_default') || $isFirst;

            if ($isDefault) {
                UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            }

            UserAddress::create([
                'user_id'       => Auth::id(),
                'label'         => $request->label,
                'nama_penerima' => $request->nama_penerima,
                'no_telp'       => $request->no_telp,
                'alamat'        => $request->alamat,
                'province_id'   => $request->province_id,
                'city_id'       => $request->city_id,
                'district_id'   => $request->district_id,
                'village_id'    => $request->village_id,
                'is_default'    => $isDefault,
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alamat berhasil ditambahkan!']);
        }

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $address = UserAddress::where('address_id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'label'         => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:100',
            'no_telp'       => 'required|string|max:20',
            'alamat'        => 'required|string',
            'province_id'   => 'required',
            'city_id'       => 'required',
            'district_id'   => 'required',
            'village_id'    => 'required',
        ]);

        DB::transaction(function () use ($request, $address) {
            if ($request->boolean('is_default')) {
                UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            }
            $address->update([
                'label'         => $request->label,
                'nama_penerima' => $request->nama_penerima,
                'no_telp'       => $request->no_telp,
                'alamat'        => $request->alamat,
                'province_id'   => $request->province_id,
                'city_id'       => $request->city_id,
                'district_id'   => $request->district_id,
                'village_id'    => $request->village_id,
                'is_default'    => $request->boolean('is_default') || $address->is_default,
            ]);
        });

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $address    = UserAddress::where('address_id', $id)->where('user_id', Auth::id())->firstOrFail();
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            UserAddress::where('user_id', Auth::id())->orderBy('created_at')->first()?->update(['is_default' => true]);
        }

        return back()->with('success', 'Alamat berhasil dihapus!');
    }

    public function setDefault($id)
    {
        DB::transaction(function () use ($id) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            UserAddress::where('address_id', $id)->where('user_id', Auth::id())->update(['is_default' => true]);
        });

        return back()->with('success', 'Alamat utama berhasil diubah!');
    }

    // API untuk modal checkout
    public function list()
    {
        $addresses = UserAddress::where('user_id', Auth::id())
            ->with(['province', 'city', 'district', 'village'])
            ->orderByDesc('is_default')
            ->get()
            ->map(fn($a) => [
                'address_id'     => $a->address_id,
                'label'          => $a->label,
                'nama_penerima'  => $a->nama_penerima,
                'no_telp'        => $a->no_telp,
                'alamat'         => $a->alamat,
                'province_id'    => $a->province_id,
                'city_id'        => $a->city_id,
                'is_default'     => $a->is_default,
                'alamat_lengkap' => $a->alamat_lengkap,
            ]);

        return response()->json($addresses);
    }
}