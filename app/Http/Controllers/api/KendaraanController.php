<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\KendaraanService;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    use ResponseAPI;

    protected $KendaraanService;

    public function __construct(KendaraanService $KendaraanService)
    {
        $this->KendaraanService = $KendaraanService;
    }

    public function allStok(Request $request)
    {
        try {
            $data = $this->KendaraanService->stokKendaraan($request);
            return $this->success('Fetched successfully.', $data);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?? 500);
        }
    }

    public function allTerjual(Request $request)
    {
        try {
            $data = $this->KendaraanService->kendaraanTerjual($request);
            return $this->success('Fetched successfully.', $data);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?? 500);
        }
    }

    public function kendaraan(Request $request, string $id)
    {
        try {
            $data = $this->KendaraanService->getKendaraan($request, $id);
            return $this->success('Fetched successfully.', $data);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?? 500);
        }
    }

    public function storeKendaraan(Request $request)
    {
        try {
            $data = $this->KendaraanService->insert($request);
            return $this->success('New Kendaraan has been stored.', $data, 201);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?? 500);
        }
    }

    public function storePenjualan(Request $request, string $id)
    {
        try {
            $data = $this->KendaraanService->insertPenjualan($request, $id);
            return $this->success('Kendaraan has been sold.', $data, 201);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode() ?? 500);
        }
    }
}
