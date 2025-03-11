<?php

namespace App\Services\Shift;

use App\Models\ShiftType;
use App\Repositories\ShiftTypeRepository;
use Exception;
use Illuminate\Http\Request;

class ShiftTypeService
{
    protected ShiftTypeRepository $shiftTypeRepository;

    public function __construct(ShiftTypeRepository $shiftTypeRepository)
    {
        $this->shiftTypeRepository = $shiftTypeRepository;
    }

    public function getActiveShiftTypes()
    {
        try {
            return $this->shiftTypeRepository->getActiveShiftTypes();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getShiftTypes(Request $request)
    {
        try {
            return $this->shiftTypeRepository->getShiftTypes($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getShiftType(int $id)
    {
        try {
            return $this->shiftTypeRepository->getShiftType($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getShiftTypeByName(string $name)
    {
        try {
            return $this->shiftTypeRepository->getShiftTypeByName($name);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createShiftType(Request $request): ?ShiftType
    {
        try {
            return $this->shiftTypeRepository->createShiftType($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateShiftType($request, int $id): ?ShiftType
    {
        try {
            return $this->shiftTypeRepository->updateShiftType($request, $id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteShiftTypes(Request $request): int
    {
        try {
            return $this->shiftTypeRepository->deleteShiftTypes($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteShiftType(Request $request)
    {
        try {
            return $this->shiftTypeRepository->deleteShiftType($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function restoreShiftType(Request $request): ShiftType
    {
        try {
            return $this->shiftTypeRepository->restoreShiftType($request);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function realDeleteShiftType(int $id): ShiftType
    {
        try {
            return $this->shiftTypeRepository->realDeleteShiftType($id);
        } catch (Exception $e) {
            throw $e;
        }
    }
}