<?php

namespace App\Services\Country;

use App\Models\Country;
use App\Repositories\CountryRepository;
use Illuminate\Http\Request;
use Exception;

class CountryService
{
    protected CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function getActiveCountries()
    {
        try {
            return $this->countryRepository->getActiveCountries();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getCountries(Request $request)
    {
        try {
            return $this->countryRepository->getCountries($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getCountry(int $id)
    {
        try {
            return $this->countryRepository->getCountry($id);
        } catch(Exception $ex){
            throw $ex;
        }
    }

    public function getCountryByName(string $name): ?Country
    {
        try {
            return $this->countryRepository->getCountryByName($name);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function createCountry(Request $request): ?Country
    {
        try {
            return $this->countryRepository->createCountry($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateCountry(Request $request, int $id): ?Country
    {
        try {
            return $this->countryRepository->updateCountry($request, $id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteCountries(Request $request): int
    {
        try {
            return $this->countryRepository->deleteCountries($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteCountry(Request $request): ?Country
    {
        try {
            return $this->countryRepository->deleteCountry($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function restoreCountry(Request $request): ?Country
    {
        try {
            return $this->countryRepository->restoreCountry($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function realDeleteCountry(Request $request): ?Country
    {
        try {
            return $this->countryRepository->realDeleteCountry($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
