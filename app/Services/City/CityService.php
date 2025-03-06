<?php

namespace App\Services\AppSettings;

use App\Models\City;
use App\Repositories\CityRepository;
use Illuminate\Http\Request;

class CityService
{
    protected CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function getActiveCities()
    {
        $ret_val = $this->cityRepository->getActiveCities();
        return $ret_val;
    }

    public function getCities(Request $request)
    {
        $ret_val = $this->cityRepository->getCities($request);
        return $ret_val;
    }

    public function getCity(int $id)
    {
        $ret_val = $this->cityRepository->getCity($id);
        return $ret_val;
    }

    public function getCityByName(string $name): City
    {
        $ret_val = $this->cityRepository->getCityByName($name);
        return $ret_val;
    }

    public function createCity(Request $request): City
    {
        $ret_val = $this->cityRepository->createCity($request);
        return $ret_val;
    }

    public function updateCity(Request $request, int $id)
    {
        $ret_val = $this->cityRepository->updateCity($request, $id);
        return $ret_val;
    }

    public function deleteCities(Request $request)
    {
        $ret_val = $this->cityRepository->deleteCities($request);
        return $ret_val;
    }

    public function deleteCity(Request $request)
    {
        $ret_val = $this->cityRepository->deleteCity($request);
        return $ret_val;
    }

    public function restoreCity(Request $request)
    {
        $ret_val = $this->cityRepository->restoreCity($request);
        return $ret_val;
    }

    public function realDeleteCity(int $id)
    {
        $ret_val = $this->cityRepository->realDeleteCity($id);
        return $ret_val;
    }
}