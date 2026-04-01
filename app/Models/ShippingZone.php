<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $table = 'shipping_zones';
    protected $primaryKey = 'zone_id';

    protected $fillable = [
        'nama_zona',
        'tipe',
        'harga_dasar',
        'harga_per_500gr',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'zone_id', 'zone_id');
    }
}