<?php

namespace App\Repository;

use App\Interfaces\KendaraanRepoInterface;
use App\Models\Kendaraan;
use App\Models\Mobil;
use App\Models\Motor;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class KendaraanRepository implements KendaraanRepoInterface
{
    public function stocks(string $tipe): ?Collection
    {
        $kendaraans = Kendaraan::whereHas($tipe, function ($query) {
            $query->whereNull('terjual_at');
        })->with($tipe, function ($query) {
            $query->whereNull('terjual_at');
        })->get();

        return $kendaraans;
    }

    public function kendaraan(string $id): ?Kendaraan
    {
        $kendaraan = Kendaraan::with([
            'motors' => function ($query) {
                $query->whereNull('terjual_at');
            }, 
            'mobils' => function ($query) {
                $query->whereNull('terjual_at');
            }
        ])->findOrFail($id);
        return $kendaraan;
    }

    public function kendaraanTerjual(string $id): ?Kendaraan
    {
        $kendaraan = Kendaraan::with([
            'motors' => function ($query) {
                $query->whereNotNull('terjual_at');
            }, 
            'mobils' => function ($query) {
                $query->whereNotNull('terjual_at');
            }
        ])->findOrFail($id);
        return $kendaraan;
    }

    public function sold(): ?Collection
    {
        $kendaraans = Kendaraan::with([
            'motors' => function ($query) {
                $query->whereNotNull('terjual_at');
            },
            'mobils' => function ($query) {
                $query->whereNotNull('terjual_at');
            }
        ])->where('terjual_count', '>', 0)->get();

        return $kendaraans;
    }

    public function soldWhere(string $tipe): ?Collection
    {
        $kendaraans = Kendaraan::whereHas($tipe, function ($query) {
            $query->whereNotNull('terjual_at');
        })->with($tipe, function ($query) {
            $query->whereNotNull('terjual_at');
        })->get();

        return $kendaraans;

        return $kendaraans;
    }

    public function insertMotor(Array $attributes): ?Kendaraan
    {
        $data = null;

        $session = DB::connection('mongodb')->getMongoClient()->startSession();
        $session->startTransaction();

        try {
            $kendaraan = Kendaraan::create([
                'nama' => $attributes['nama'],
                'tahun' => $attributes['tahun'],
                'warna' => $attributes['warna'],
                'harga' => $attributes['harga'],
                'stok_count' => $attributes['stok_count'],
                'terjual_count' => 0,
            ]);

            $motor = new Motor([
                'mesin' => $attributes['mesin'],
                'no_mesin' => null,
                'tipe_suspensi' => $attributes['tipe_suspensi'],
                'tipe_transmisi' => $attributes['tipe_transmisi'],
                'terjual_at' => null,
            ]);
            $kendaraan->motors()->save($motor);

            $session->commitTransaction();
            $data = $kendaraan->load([
                'motors' => function($query) use ($motor) {
                    $query->where('_id', $motor->_id);
                }
            ]);

            return $data;
        } catch (Exception $e) {
            $session->abortTransaction();
            throw new Exception($e->getMessage(), 422);
        } finally {
            $session->endSession();
        }
    }

    public function insertMobil(Array $attributes): ?Kendaraan
    {
        $data = null;

        $session = DB::connection('mongodb')->getMongoClient()->startSession();
        $session->startTransaction();

        try {
            $kendaraan = Kendaraan::create([
                'nama' => $attributes['nama'],
                'tahun' => $attributes['tahun'],
                'warna' => $attributes['warna'],
                'harga' => $attributes['harga'],
                'stok_count' => $attributes['stok_count'],
                'terjual_count' => 0,
            ]);

            $mobil = new Mobil([
                'mesin' => $attributes['mesin'],
                'no_mesin' => null,
                'kapasitas_penumpang' => $attributes['kapasitas_penumpang'],
                'tipe' => $attributes['tipe'],
                'terjual_at' => null,
            ]);
            $kendaraan->mobils()->save($mobil);

            $session->commitTransaction();
            $data = $kendaraan->load([
                'mobils' => function($query) use ($mobil) {
                    $query->where('_id', $mobil->_id);
                }
            ]);

            return $data;
        } catch (Exception $e) {
            $session->abortTransaction();
            throw new Exception($e->getMessage(), 422);
        } finally {
            $session->endSession();
        }
    }

    public function insertPenjualanMotor(Array $attributes, string $kendaraan_id): ?Kendaraan
    {
        $data = null;

        $session = DB::connection('mongodb')->getMongoClient()->startSession();
        $session->startTransaction();

        try {
            $kendaraan = Kendaraan::findOrFail($kendaraan_id);

            $kendaraan->stok_count = $kendaraan->stok_count - 1;
            $kendaraan->terjual_count = $kendaraan->terjual_count + 1;
            $kendaraan->save();

            $motor = new Motor([
                'mesin' => $attributes['mesin'], 
                'no_mesin' => $attributes['no_mesin'], 
                'tipe_suspensi' => $attributes['tipe_suspensi'], 
                'tipe_transmisi' => $attributes['tipe_transmisi'], 
                'terjual_at' => Carbon::now(),
            ]);
            $kendaraan->motors()->save($motor);

            $session->commitTransaction();
            $data = $kendaraan->load([
                'motors' => function($query) use ($motor) {
                    $query->where('_id', $motor->_id);
                }
            ]);

            return $data;
        } catch (Exception $e) {
            $session->abortTransaction();
            throw new Exception($e->getMessage(), 422);
        } finally {
            $session->endSession();
        }
    }

    public function insertPenjualanMobil(Array $attributes, string $kendaraan_id): ?Kendaraan
    {
        $data = null;

        $session = DB::connection('mongodb')->getMongoClient()->startSession();
        $session->startTransaction();

        try {
            $kendaraan = Kendaraan::findOrFail($kendaraan_id);

            $kendaraan->stok_count = $kendaraan->stok_count - 1;
            $kendaraan->terjual_count = $kendaraan->terjual_count + 1;
            $kendaraan->save();

            $mobil = new Mobil([
                'mesin' => $attributes['mesin'],
                'no_mesin' => $attributes['no_mesin'], 
                'kapasitas_penumpang' => $attributes['kapasitas_penumpang'],
                'tipe' => $attributes['tipe'],
                'terjual_at' => Carbon::now(),
            ]);
            $kendaraan->mobils()->save($mobil);

            $session->commitTransaction();
            $data = $kendaraan->load([
                'mobils' => function($query) use ($mobil) {
                    $query->where('_id', $mobil->_id);
                }
            ]);

            return $data;
        } catch (Exception $e) {
            $session->abortTransaction();
            throw new Exception($e->getMessage(), 422);
        } finally {
            $session->endSession();
        }
    }
}
