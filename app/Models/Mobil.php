<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;

class Mobil extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'mobils';

    protected $fillable = [
        'kendaraan_id', 'mesin', 'no_mesin', 'kapasitas_penumpang', 'tipe', 'terjual_at'
    ];

    protected $dates = ['terjual_at'];

    // public function setTerjualAtAttribute($value)
    // {
    //     $this->attributes['terjual_at'] = Carbon::parse($value);
    // }

    public function getTerjualAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function kendaraan()
    {
        return $this->belongsTo('App\Models\Kendaraan');
    }
}
