<?php

namespace App\Services\Subdomain;

use App\Models\Subdomain;
use App\Repositories\SubdomainRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SubdomainService
{
    protected SubdomainRepository $subdomainRepository;

    public function __construct(SubdomainRepository $subdomainRepository)
    {
        $this->subdomainRepository = $subdomainRepository;
    }

    public function getActiveSubdomains(): Collection
    {
        try {
            return $this->subdomainRepository->getActiveSubdomains();
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getSubdomains(Request $request): Collection
    {
        try {
            return $this->subdomainRepository->getSubdomains($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getSubdomain(int $id): Subdomain
    {
        try {
            return $this->subdomainRepository->getSubdomain($id);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getSubdomainByName(string $name): Subdomain
    {
        try {
            return $this->subdomainRepository->getSubdomainByName($name);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function createSubdomain(Request $request): Subdomain
    {
        try {
            return $this->subdomainRepository->createSubdomain($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function updateSubdomain(Request $request, int $id): ?Subdomain
    {
        try {
            return $this->subdomainRepository->updateSubdomain($request, $id);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function deleteSubdomains(Request $request): int
    {
        try {
            return $this->subdomainRepository->deleteSubdomains($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function deleteSubdomain(Request $request): Subdomain
    {
        try {
            return $this->subdomainRepository->deleteSubdomain($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function restoreSubdomain(Request $request): Subdomain
    {
        try {
            return $this->subdomainRepository->restoreSubdomain($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }
}