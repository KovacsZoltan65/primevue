<?php

namespace App\Repositories;

use App\Interfaces\SettingsMetadataRepositoryInterface;
use App\Models\SettingsMetadata;
use App\Services\CacheService;
use App\Traits\Functions;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Exception;
use DB;

class SettingsMetadataRepository extends BaseRepository implements SettingsMetadataRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag ='settings_metadata';

    public function __construct(CacheService $cacheService)
    {
        $this->tag = SettingsMetadata::getTag();
        $this->cacheService = $cacheService;
    }

    public function getActiveMetadata(): array
    {
        try {
            $model = $this->model();
            $settings = $model::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->where('active', '=', 1)
                ->get()->toArray();

            return $settings;
        } catch(Exception $ex) {
            // Hiba logolás az ActivityController segítségével
            $this->logError($ex, 'getActiveMetadata error', []);
            // Hiba továbbítása
            throw $ex;
        }
    }

    public function getMetadatas(Request $request): Collection
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $metadataQuery = SettingsMetadata::search($request);
                return $metadataQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getMetadatas error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getMetadata(int $id): SettingsMetadata
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return SettingsMetadata::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getMetadata error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getMetadataByKey(string $key): SettingsMetadata
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $key);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($key) {
                return SettingsMetadata::where('name', '=', $key)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getMetadataByKey error', ['key' => $key]);
            throw $ex;
        }
    }

    public function createMetadata(Request $request): ?SettingsMetadata
    {
        try {
            $metadata = null;

            DB::transaction(function()use($request, &$metadata) {
                // 1. Cég létrehozása
                $metadata = SettingsMetadata::create($request->all());

                // 2. Kapcsolódó rekordok létrehozása (pl. alapértelmezett beállítások)
                $this->createDefaultSettings($metadata);

                // 3. Cache törlése, ha releváns
                $this->cacheService->forgetAll($this->tag);
            });

            return $metadata;
        } catch(Exception $ex) {
            $this->logError($ex, 'createMetadata error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updateMetadata(Request $request): ?SettingsMetadata
    {
        try {
            $metadata = null;

            DB::transaction(function()use($request, &$metadata) {
                $metadata = SettingsMetadata::lockForUpdate()->findOrFail($request->id);
                $metadata->update($request->all());
                $metadata->refresh();
            });

            return $metadata;
        } catch(Exception $ex) {
            $this->logError($ex, 'updateMetadata error', ['request' => $request->all()]);
            throw $ex;
        }
    }





    public function model(): string
    {
        return SettingsMetadata::class;
    }

    public function boot(): void
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
