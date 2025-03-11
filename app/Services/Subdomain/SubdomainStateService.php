<?php

namespace App\Services\Subdomain;

use App\Models\SubdomainState;
use App\Repositories\SubdomainStateRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SubdomainStateService
{
    protected SubdomainStateRepository $subdomainStateRepository;

    public function __construct(SubdomainStateRepository $subdomainStateRepository)
    {
        $this->subdomainStateRepository = $subdomainStateRepository;
    }

    public function getActiveStates(): array
    {
        try {
            return $this->subdomainStateRepository->getActiveStates();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getSubdomainStates(Request $request): Collection
    {
        try {
            return $this->subdomainStateRepository->getSubdomainStates($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getSubdomainState(int $id): SubdomainState
    {
        try {
            return $this->subdomainStateRepository->getSubdomainState($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getSubdomainStateByName(string $name): SubdomainState
    {
        try {
            return $this->subdomainStateRepository->getSubdomainStateByName($name);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createSubdomainState(Request $request): SubdomainState
    {
        try {
            return $this->subdomainStateRepository->createSubdomainState($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateSubdomainState(Request $request, int $id): ?SubdomainState
    {
        try {
            return $this->subdomainStateRepository->updateSubdomainState($request, $id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteSubdomainStates(Request $request): int
    {
        try {
            return $this->subdomainStateRepository->deleteSubdomainStates($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteSubdomainState(Request $request): SubdomainState
    {
        try {
            return $this->subdomainStateRepository->deleteSubdomainState($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function restoreSubdomainState(Request $request): SubdomainState
    {
        try {
            return $this->subdomainStateRepository->restoreSubdomainState($request);
        } catch (Exception $e) {
            throw $e;
        }
    }
}