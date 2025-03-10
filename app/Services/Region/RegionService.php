<?php

namespace App\Services\Region;

use App\Models\Region;
use App\Repositories\RegionRepository;
use Exception;
use Illuminate\Http\Request;

class RegionService
{
    protected RegionRepository $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function deleteRegion(Request $request): ?Region
    {
        try {
            return $this->regionRepository->deleteRegion($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function restoreRegion(Request $request): ?Region
    {
        try {
            return $this->regionRepository->restoreRegion($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function realDeleteREgion(int $id): ?Region
    {
        try {
            return $this->regionRepository->realDeleteRegion($id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }
}
