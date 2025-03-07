<?php

namespace App\Services\Company;

use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Company;
use Exception;

class CompanyService
{
    protected CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function getActiveCompanies(): Collection
    {
        try {
            return $this->companyRepository->getActiveCompanies();
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getCompanies(Request $request): Collection
    {
        try{
            return $this->companyRepository->getCompanies($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getCompany(int $id): Company
    {
        try{
            return $this->companyRepository->getCompany($id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getCompanyByName(string $name): Company
    {
        try {
            return $this->companyRepository->getCompanyByName($name);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function createCompany(Request $request): Company
    {
        try {
            return $this->companyRepository->createCompany($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function updateCompany($request, int $id): Company
    {
        try {
            return $this->companyRepository->updateCompany($request, $id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function deleteCompanies(Request $request): int
    {
        try {
            return $this->companyRepository->deleteCompanies($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function deleteCompany(Request $request): ?Company
    {
        try {
            return $this->companyRepository->deleteCompany($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function restoreCompany(Request $request): ?Company
    {
        try {
            return $this->companyRepository->restoreCompany($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function realDeleteCompany(int $id): ?Company
    {
        try {
            return $this->companyRepository->realDeleteCompany($id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }
}