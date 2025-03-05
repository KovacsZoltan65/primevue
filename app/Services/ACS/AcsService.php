<?php

namespace App\Services\ACS;

use App\Models\ACS;
use App\Repositories\ACSRepository;
use Illuminate\Http\Request;

class AcsService
{
    protected ACSRepository $acsRepository;

    public function __construct(ACSRepository $acsRepository)
    {
        $this->acsRepository = $acsRepository;
    }

    public function getActiveACSs(): Array
    {
        $acs = $this->acsRepository->getActiveACSs();

        return $acs;
    }

    public function getACSs(Request $request): Array
    {
        $acs = $this->acsRepository->getACSs($request);

        return $acs;
    }

    public function getACS(int $id): ACS
    {
        $acs = $this->acsRepository->getACS($id);

        return $acs;
    }

    public function getACSByName(string $name): ACS
    {
        $acs = $this->acsRepository->getACSByName($name);

        return $acs;
    }

    public function createACS(Request $request): ACS
    {
        $acs = $this->acsRepository->createACS($request);

        return $acs;
    }

    public function updateACS($request, int $id): ?ACS
    {
        $acs = $this->acsRepository->updateACS($request, $id);

        return $acs;
    }

    public function deleteACSs(Request $request): int
    {
        $acs = $this->acsRepository->deleteACSs($request);

        return $acs;
    }

    public function deleteACS(Request $request): ?ACS
    {
        $acs = $this->acsRepository->deleteACS($request);

        return $acs;
    }

    public function restoreACS(Request $request): ACS
    {
        $acs = $this->acsRepository->restoreACS($request);

        return $acs;
    }

    public function realDeleteACS(int $id): ?ACS
    {
        $acs = $this->acsRepository->realDeleteACS($id);

        return $acs;
    }
}
