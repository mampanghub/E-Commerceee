<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'kurir_id',
        'total_harga',
        'ongkir',
        'shipping_address',
        'catatan',
        'nama_penerima',
        'no_telp_penerima',
        'status',
        'nama_kurir',
        'nomor_resi',
        'snap_token',
        // kolom baru shipping
        'zone_id',
        'shipping_option_id',
        'estimasi_hari',
        'estimasi_tiba',
        'estimasi_tiba_max',
        'berat_total_gram',
    ];

    protected $casts = [
        'dikirim_at'    => 'datetime',
        'estimasi_tiba'      => 'date',
        'estimasi_tiba_max'  => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function kurir()
    {
        return $this->belongsTo(User::class, 'kurir_id', 'user_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class, 'zone_id', 'zone_id');
    }

    public function shippingOption()
    {
        return $this->belongsTo(ShippingOption::class, 'shipping_option_id', 'option_id');
    }
}
