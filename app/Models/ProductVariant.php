<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $primaryKey = 'variant_id';

    protected $fillable = [
        'product_id',
        'nama_varian',
        'stok',
        'berat',
        'harga_tambahan',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function stockLogs()
    {
        return $this->hasMany(\App\Models\StockLog::class, 'variant_id', 'variant_id');
    }
}
