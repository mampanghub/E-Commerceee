<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOption extends Model
{
    protected $table = 'shipping_options';
    protected $primaryKey = 'option_id';

    protected $fillable = [
        'label',
        'kurang_hari',
        'persen_tambahan',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_option_id', 'option_id');
    }
}