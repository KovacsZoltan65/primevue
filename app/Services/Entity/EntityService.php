<?php

namespace App\Services\Entity;

use App\Models\Entity;
use App\Repositories\EntityRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class EntityService
{
    protected EntityRepository $entityRepository;

    public function __construct(EntityRepository $entityRepository)
    {
        try {
            $this->entityRepository = $entityRepository;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getActiveCompanies(): array
    {
        try {
            return $this->entityRepository->getActiveCompanies();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getEntities(Request $request): Collection
    {
        try {
            return $this->entityRepository->getEntities($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getEntity(int $id): Entity
    {
        try {
            return $this->entityRepository->getEntity($id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getEntityByName(string $name): Entity
    {
        try {
            return $this->entityRepository->getEntityByName($name);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function createEntity(Request $request): Entity
    {
        try {
            return $this->entityRepository->createEntity($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateEntity(Request $request, int $id): ?Entity
    {
        try {
            return $this->entityRepository->updateEntity($request, $id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteEntities(Request $request): bool
    {
        try {
            return $this->entityRepository->deleteEntities($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteEntity(Request $request): Collection
    {
        try {
            return $this->entityRepository->deleteEntity($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function restoreEntity(Request $request)
    {
        try {
            return $this->entityRepository->restoreEntity($request);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}