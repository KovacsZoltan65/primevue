<?php

namespace App\Services\AppSettings;

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
        return $this->companyRepository->getCompanies($request);
    }

    public function getCompany(int $id): Company
    {
        return $this->companyRepository->getCompany($id);
    }

    public function getCompanyByName(string $name): Company
    {
        return $this->companyRepository->getCompanyByName($name);
    }

    public function createCompany(Request $request): Company
    {
        return $this->companyRepository->createCompany($request);
    }

    public function updateCompany($request, int $id): Company
    {
        return $this->companyRepository->updateCompany($request, $id);
    }

    public function deleteCompanies(Request $request): int
    {
        return $this->companyRepository->deleteCompanies($request);
    }

    public function deleteCompany(Request $request): ?Company
    {
        return $this->companyRepository->deleteCompany($request);
    }

    public function restoreCompany(Request $request): ?Company
    {
        return $this->companyRepository->restoreCompany($request);
    }

    public function realDeleteCompany(int $id): ?Company
    {
        return $this->companyRepository->realDeleteCompany($id);
    }
}