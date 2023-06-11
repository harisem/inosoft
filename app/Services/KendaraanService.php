<?php

namespace App\Services;

use App\Interfaces\KendaraanRepoInterface;
use App\Models\Kendaraan;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class KendaraanService
{
    use ResponseAPI;

    protected $KendaraanRepository;

    public function __construct(KendaraanRepoInterface $KendaraanRepository)
    {
        $this->KendaraanRepository = $KendaraanRepository;
    }

    public function getKendaraan(Request $request, string $id): ?Kendaraan
    {
        $queryString = $request->query('terjual');

        if ($queryString && $queryString == 'true') {
            $data = $this->KendaraanRepository->kendaraanTerjual($id);
        } else {
            $data = $this->KendaraanRepository->kendaraan($id);
        }

        if ($data && $data->motors->isEmpty()) unset($data->motors);
        if ($data && $data->mobils->isEmpty()) unset($data->mobils);

        return $data;
    }

    public function stokKendaraan(Request $request): ?Collection
    {
        $data = null;
        $queryStringTipe = $this->cekQueryParams($request);

        if ($queryStringTipe == 'mobil') {
            $data = $this->KendaraanRepository->stocks('mobils');
        }
        
        if ($queryStringTipe == 'motor') {
            $data = $this->KendaraanRepository->stocks('motors');
        }

        return $data;
    }

    public function kendaraanTerjual(Request $request): ?Collection
    {
        $data = null;
        $queryStringTipe = $request->query('tipe');

        if ($queryStringTipe == 'mobil') {
            $data = $this->KendaraanRepository->soldWhere('mobils');
        } elseif ($queryStringTipe == 'motor') {
            $data = $this->KendaraanRepository->soldWhere('motors');
        } else {
            $data = $this->KendaraanRepository->sold();
        }

        return $data;
    }

    public function insert(Request $request): ?Kendaraan
    {
        $data = null;
        $queryStringTipe = $this->cekQueryParams($request);

        if ($queryStringTipe == 'motor') {
            $rules = [
                'nama' => 'required',
                'tahun' => 'required',
                'warna' => 'required',
                'harga' => 'required',
                'stok_count' => 'required',
                'mesin' => 'required',
                'tipe_suspensi' => 'required',
                'tipe_transmisi' => 'required',
            ];
            $validated = $this->validasiForm($request, $rules);
            $data = $this->KendaraanRepository->insertMotor($validated);
        }
        
        if ($queryStringTipe == 'mobil') {
            $rules = [
                'nama' => 'required',
                'tahun' => 'required',
                'warna' => 'required',
                'harga' => 'required',
                'stok_count' => 'required',
                'mesin' => 'required',
                'kapasitas_penumpang' => 'required',
                'tipe' => 'required',
            ];
            $validated = $this->validasiForm($request, $rules);
            $data = $this->KendaraanRepository->insertMobil($validated);
        }

        return $data;
    }

    public function insertPenjualan(Request $request, string $id): ?Kendaraan
    {
        $data = null;
        $queryStringTipe = $this->cekQueryParams($request);

        if ($queryStringTipe == 'motor') {
            $rules = [
                'mesin' => 'required',
                'no_mesin' => 'required',
                'tipe_suspensi' => 'required',
                'tipe_transmisi' => 'required',
            ];
            $validated = $this->validasiForm($request, $rules);
            $data = $this->KendaraanRepository->insertPenjualanMotor($validated, $id);
        }
        
        if ($queryStringTipe == 'mobil') {
            $rules = [
                'mesin' => 'required',
                'no_mesin' => 'required',
                'kapasitas_penumpang' => 'required',
                'tipe' => 'required',
            ];
            $validated = $this->validasiForm($request, $rules);
            $data = $this->KendaraanRepository->insertPenjualanMobil($validated, $id);
        }

        return $data;
    }

    public function cekQueryParams(Request $request)
    {
        $queryString = $request->query('tipe');
        $url = $request->fullUrl() . '?tipe=motor';
        $availableTipe = ['motor', 'mobil'];
        
        if ($queryString) {
            if (in_array($queryString, $availableTipe)) {
                return $queryString;
            } else {
                throw new Exception('The given \'tipe\' does not match the available \'tipe\'.  Available \'tipe\' are \'motor\' or \'mobil\'. eg. ' . $url, 422);
            }
        } else {
            throw new Exception('The \'tipe\' parameter is required. Please provide  a \'tipe\' keyword. eg. ' . $url, 422);
        }
    }

    public function validasiForm(Request $request, array $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->getMessageBag();
            throw new Exception($errors->first(), 422);
        }

        return $request->all();
    }
}
