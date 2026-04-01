<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'variant_id',
        'stok_lama',
        'stok_baru',
        'jumlah',
        'tipe',
        'keterangan',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'variant_id');
    }
}