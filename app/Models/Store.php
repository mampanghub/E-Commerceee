<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Store extends Model
{
    protected $table = 'stores';
    protected $primaryKey = 'store_id';

    protected $fillable = [
        'user_id',
        'nama_toko',
        'status',
        'deskripsi',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'saldo',
        'alamat'
    ];

    // RELASI
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'store_id');
    }
}
