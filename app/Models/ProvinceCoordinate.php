<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProvinceCoordinate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'province_code',
        'province_name',
        'latitude',
        'longitude',
    ];
}
