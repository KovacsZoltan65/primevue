<?php

namespace App\Services\AppSettings;

use App\Models\Entity;
use App\Repositories\HierarchyRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Exception;

class HierarchyService
{
    protected HierarchyRepository $hierarchyRepository;

    public function __construct(HierarchyRepository $hierarchyRepository)
    {
        $this->hierarchyRepository = $hierarchyRepository;
    }

    public function addParent(Request $request, $childId): ?Entity
    {
        try {
            $this->hierarchyRepository->addParent($request, $childId);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getHierarchy($entityId)
    {
        try {
            return $this->hierarchyRepository->getHierarchy($entityId);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function removeChild(int $parentId, int $entityId): int
    {
        try {
            return $this->hierarchyRepository->removeChild($parentId, $entityId);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getBigBosses()
    {
        try {
            return $this->hierarchyRepository->getBigBosses();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}