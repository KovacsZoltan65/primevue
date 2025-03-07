<?php

namespace App\Services\City;

use App\Models\City;
use App\Repositories\CityRepository;
use Illuminate\Http\Request;
use Exception;

class CityService
{
    protected CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function getActiveCities()
    {
        try {
            $ret_val = $this->cityRepository->getActiveCities();
            return $ret_val;
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getCities(Request $request)
    {
        try {
            $ret_val = $this->cityRepository->getCities($request);
            return $ret_val;
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getCity(int $id)
    {
        try {
            $ret_val = $this->cityRepository->getCity($id);
            return $ret_val;
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getCityByName(string $name): City
    {
        try {
            $ret_val = $this->cityRepository->getCityByName($name);
            return $ret_val;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function createCity(Request $request): City
    {
        try {
            $ret_val = $this->cityRepository->createCity($request);
            return $ret_val;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function updateCity(Request $request, int $id)
    {
        try {
            $ret_val = $this->cityRepository->updateCity($request, $id);
            return $ret_val;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function deleteCities(Request $request)
    {
        try {
            $ret_val = $this->cityRepository->deleteCities($request);
            return $ret_val;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function deleteCity(Request $request)
    {
        try {
            $ret_val = $this->cityRepository->deleteCity($request);
            return $ret_val;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function restoreCity(Request $request)
    {
        try {
            $ret_val = $this->cityRepository->restoreCity($request);
            return $ret_val;
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function realDeleteCity(int $id)
    {
        try {
            $ret_val = $this->cityRepository->realDeleteCity($id);
            return $ret_val;
        } catch(Exception $ex) {
            throw $ex;
        }
    }
}
