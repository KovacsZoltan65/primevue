<?php

namespace App\Services\Setting;

use App\Models\SettingsMetadata;
use App\Repositories\SettingsMetadataRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SettingsMetadataService
{
    protected SettingsMetadataRepository $settingsMetadataRepository;

    public function __construct(SettingsMetadataRepository $settingsMetadataRepository)
    {
        $this->settingsMetadataRepository = $settingsMetadataRepository;
    }
    
    public function getActiveMetadata(): array
    {
        try {
            return $this->settingsMetadataRepository->getActiveMetadata();
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getMetadatas(Request $request): Collection
    {
        try {
            return $this->settingsMetadataRepository->getMetadatas($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getMetadata(int $id): SettingsMetadata
    {
        try {
            return $this->settingsMetadataRepository->getMetadata($id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function getMetadataByKey(string $key): SettingsMetadata
    {
        try {
            return $this->settingsMetadataRepository->getMetadataByKey($key);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function createMetadata(Request $request): ?SettingsMetadata
    {
        try {
            return $this->settingsMetadataRepository->createMetadata($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function updateMetadata(Request $request, int $id): ?SettingsMetadata
    {
        try {
            return $this->settingsMetadataRepository->updateMetadata($request, $id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function deleteMetaDatas(Request $request): int
    {
        try {
            return $this->settingsMetadataRepository->deleteMetaDatas($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function deleteMetaData(Request $request): ?SettingsMetadata
    {
        try {
            return $this->settingsMetadataRepository->deleteMetaData($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function restoreMetaData(Request $request): ?SettingsMetadata
    {
        try {
            return $this->settingsMetadataRepository->restoreMetaData($request);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }

    public function realDeleteMetaData(int $id): ?SettingsMetadata
    {
        try {
            return $this->settingsMetadataRepository->realDeleteMetaData($id);
        } catch( Exception $ex ) {
            throw $ex;
        }
    }
}