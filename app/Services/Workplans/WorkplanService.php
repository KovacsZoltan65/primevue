<?php

namespace App\Services\Workplans;

use App\Models\Workplan;
use App\Repositories\WorkplanRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class WorkplanService
{
    protected WorkplanRepository $workplanRepository;

    public function __construct(WorkplanRepository $workplanRepository)
    {
        $this->workplanRepository = $workplanRepository;
    }

    public function getActiveWorkplans(): Array
    {
        $workplans = $this->workplanRepository->getActiveWorkplans();

        return $workplans;
    }

    public function getWorkplans(Request $request)
    {
        $workplans = $this->workplanRepository->getWorkplans($request);

        return $workplans;
    }

    public function getWorkplan(int $id): Workplan
    {
        $workplan = $this->workplanRepository->getWorkplan($id);

        return $workplan;
    }

    public function getWorkplanByName(string $name): Workplan
    {
        $workplan = $this->workplanRepository->getWorkplanByName($name);

        return $workplan;
    }

    public function createWorkplan(Request $request): ?Workplan
    {
        $workplan = $this->workplanRepository->createWorkplan($request);

        return $workplan;
    }

    public function updateWorkplan($request, int $id): ?Workplan
    {
        $workplan = $this->workplanRepository->updateWorkplan($request, $id);

        return $workplan;
    }

    public function deleteWorkplans(Request $request): int
    {
        $deletedCount = $this->workplanRepository->deleteWorkplans($request);

        return $deletedCount;
    }

    public function deleteWorkplan(Request $request): ?Workplan
    {
        $workplan = $this->workplanRepository->deleteWorkplan($request);

        return $workplan;
    }

    public function restoreWorkplan(Request $request): ?Workplan
    {
        $workplan = $this->workplanRepository->restoreWorkplan($request);

        return $workplan;
    }

    public function realDeleteWorkplan(int $id): Workplan
    {
        $workplan = $this->workplanRepository->realDeleteWorkplan($id);

        return $workplan;
    }
}
