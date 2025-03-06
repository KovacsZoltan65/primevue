<?php

namespace App\Repositories;

use App\Models\ACS;
use App\Interfaces\ACSRepositoryInterface;
use App\Services\CacheService;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Traits\Functions;
use Override;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ACSRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ACSRepository extends BaseRepository implements ACSRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'acss';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = ACS::getTag();

        $this->cacheService = $cacheService;
    }

    public function getActiveACSs(): Array
    {
        try {
            $model = $this->model();
            $acss = $model::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->where('active','=',1)
                ->get()->toArray();

            return $acss;
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'getActiveACSs error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function getACSs(Request $request): Array
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $acsQuery = ACS::search($request);
                return $acsQuery->get();
            });
        } catch (Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'getACSs error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function getACS(int $id): ACS
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return ACS::findOrFail($id);
            });
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'getACS error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function getACSByName(string $name): ACS
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return ACS::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'getACSByName error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function createACS(Request $request): ?ACS
    {
        try {
            $acs = null;

            DB::transaction(function() use($request, &$acs) {
                // 1. Cég létrehozása
                $acs = ACS::create($request->all());

                // 2. Kapcsolódó rekordok létrehozása (pl. alapértelmezett beállítások)
                $this->createDefaultSettings($acs);

                // 3. Cache törlése, ha releváns
                $this->cacheService->forgetAll($this->tag);
            });
            return $acs;
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'createACS error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function updateACS($request, int $id): ?ACS
    {
        try {
            $acs = null;
            DB::transaction(function() use($request, $id, &$acs) {
                $acs = ACS::lockForUpdate()->findOrFail($id);
                $acs->update($request->all());
                $acs->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $acs;
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'updateACS error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function deleteACSs(Request $request): int
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:roles,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            $ids = $validated['ids'];
            $deletedCount = 0;

            DB::transaction(function () use ($ids, &$deletedCount) {
                $acss = ACS::whereIn('id', $ids)->lockForUpdate()->get();

                $deletedCount = $acss->each(function ($acs) {
                    $acs->delete();
                })->count();

                // Cache törlése, ha szükséges
                $this->cacheService->forgetAll($this->tag);
            });

            return $deletedCount;
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'deleteACSs error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function deleteACS(Request $request): ?ACS
    {
        try {
            $acs = null;
            DB::transaction(function() use($request, &$acs) {
                $acs = ACS::lockForUpdate()->findOrFail($request->id);
                $acs->delete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $acs;
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'deleteACS error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function restoreACS(Request $request): ACS
    {
        try {
            $acs = new ACS();
            DB::transaction(function() use($request, &$acs) {
                $acs = ACS::withTrashed()->lockForUpdate()->findOrFail($request->id);
                $acs->restore();

                $this->cacheService->forgetAll($this->tag);
            });

            return $acs;
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex,'restoreACS error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function realDeleteACS(int $id): ?ACS
    {
        try {
            $acs = null;
            DB::transaction(function() use($id, &$acs) {
                $acs = ACS::withTrashed()->lockForUpdate()->findOrFail($id);
                $acs->forceDelete();

                $this->cacheService->forgetAll($this->tag);
            });

            return $acs;
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex,'realDeleteACS error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    private function createDefaultSettings(ACS $acs): void
    {
        //
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    #[Override]
    public function model()
    {
        return ACS::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    #[Override]
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
