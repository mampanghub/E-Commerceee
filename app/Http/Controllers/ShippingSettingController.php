<?php

namespace App\Http\Controllers;

use App\Models\ShippingSetting;
use Illuminate\Http\Request;

class ShippingSettingController extends Controller
{
    public function index()
    {
        $settings = ShippingSetting::all()->keyBy('key');
        return view('admin.shipping-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tarif_per_km'    => 'required|numeric|min:0',
            'tarif_per_500gr' => 'required|numeric|min:0',
            'jarak_minimum'   => 'required|numeric|min:1',
        ]);

        ShippingSetting::set('tarif_per_km',    (float) $request->tarif_per_km);
        ShippingSetting::set('tarif_per_500gr', (float) $request->tarif_per_500gr);
        ShippingSetting::set('jarak_minimum',   (float) $request->jarak_minimum);

        return back()->with('success', 'Pengaturan ongkir berhasil disimpan!');
    }
}
