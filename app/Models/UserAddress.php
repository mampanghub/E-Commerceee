<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $primaryKey = 'address_id';

    protected $fillable = [
        'user_id', 'label', 'nama_penerima', 'no_telp',
        'alamat', 'province_id', 'city_id', 'district_id', 'village_id', 'is_default',
    ];

    protected $casts = ['is_default' => 'boolean'];

    public function user() { return $this->belongsTo(User::class, 'user_id', 'user_id'); }
    public function province() { return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'province_id', 'code'); }
    public function city() { return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'city_id', 'code'); }
    public function district() { return $this->belongsTo(\Laravolt\Indonesia\Models\District::class, 'district_id', 'code'); }
    public function village() { return $this->belongsTo(\Laravolt\Indonesia\Models\Village::class, 'village_id', 'code'); }

    public function getAlamatLengkapAttribute(): string
    {
        return collect([
            $this->alamat,
            optional($this->village)->name,
            optional($this->district)->name,
            optional($this->city)->name,
            optional($this->province)->name,
        ])->filter()->implode(', ');
    }
}
