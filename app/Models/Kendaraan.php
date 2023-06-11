<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Kendaraan extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'kendaraans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'tahun', 'warna', 'harga', 'stok_count', 'terjual_count'
    ];

    public function mobils()
    {
        return $this->hasMany('App\Models\Mobil');
    }

    public function motors()
    {
        return $this->hasMany('App\Models\Motor');
    }
}
