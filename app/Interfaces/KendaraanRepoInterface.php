<?php

namespace App\Interfaces;

use App\Models\Kendaraan;
use Illuminate\Support\Collection;

interface KendaraanRepoInterface
{
    function stocks(string $tipe): ?Collection;
    function kendaraan(string $id): ?Kendaraan;
    function kendaraanTerjual(string $id): ?Kendaraan;
    function sold(): ?Collection;
    function soldWhere(string $tipe): ?Collection;
    function insertMotor(Array $attributes): ?Kendaraan;
    function insertMobil(Array $attributes): ?Kendaraan;
    function insertPenjualanMotor(Array $attributes, string $kendaraan_id): ?Kendaraan;
    function insertPenjualanMobil(Array $attributes, string $kendaraan_id): ?Kendaraan;
}
