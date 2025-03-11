<?php

namespace App\Services\Shift;

use App\Models\EntityShift;
use App\Repositories\EntityShiftRepository;
use Exception;
use Illuminate\Http\Request;

class EntityShiftService
{
    protected EntityShiftRepository $entityShiftRepository;

    public function __construct(EntityShiftRepository $entityShiftRepository)
    {
        $this->entityShiftRepository = $entityShiftRepository;
    }

    public function getActiveEntityShifts(): array
    {
        try {
            return $this->entityShiftRepository->getActiveEntityShifts();
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getEntityShift(int $id): EntityShift
    {
        try {
            return $this->entityShiftRepository->getEntityShift($id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getEntityShiftByName(string $name): EntityShift
    {
        try {
            return $this->entityShiftRepository->getEntityShiftByName($name);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function createEntityShift(Request $request): ?EntityShift
    {
        try {
            return $this->entityShiftRepository->createEntityShift($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function updateEntityShift($request, int $id): ?EntityShift
    {
        try {
            return $this->entityShiftRepository->updateEntityShift($request, $id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function deleteEntitiesShifts(Request $request): int
    {
        try {
            return $this->entityShiftRepository->deleteEntitiesShifts($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function deleteEntityShift(Request $request): EntityShift
    {
        try {
            return $this->entityShiftRepository->deleteEntityShift($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function restoreEntityShift(Request $request): EntityShift
    {
        try {
            return $this->entityShiftRepository->restoreEntityShift($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function realDeleteEntityShift(int $id): EntityShift
    {
        try {
            return $this->entityShiftRepository->realDeleteEntityShift($id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }
}