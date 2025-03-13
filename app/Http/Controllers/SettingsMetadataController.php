<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingsMetadataRequest;
use App\Http\Requests\UpdateSettingsMetadataRequest;
use App\Http\Requests\GetSettingsMetadataRequest;
use App\Http\Resources\SettingsMetadataResource;
use App\Models\SettingsMetadata;
use App\Services\Setting\SettingsMetadataService;
use App\Traits\Functions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Validation\ValidationException;
use Exception;

class SettingsMetadataController extends Controller
{
    use AuthorizesRequests, Functions;

    protected SettingsMetadataService $metadataService;

    protected string $tag = '';

    public function __construct(SettingsMetadataService $settingsMetadataService) {
        $this->metadataService = $settingsMetadataService;
        $this->tag = SettingsMetadata::getTag();
    }

    public function index(Request $request)
    {
        return response()->json(SettingsMetadata::all());
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('key', 'like', "%{$search}%");
        });
    }

    public function getActiveMetadata(Request $request): JsonResponse
    {
        try {
            $metadata = $this->metadataService->getActiveMetadata();

            return response()->json($metadata, Response::HTTP_OK);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getActiveMetadata query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getActiveMetadata general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMetadatas(Request $request): JsonResponse
    {
        try {
            $_metadatas = $this->metadataService->getMetadatas($request);
            $metadatas = SettingsMetadataResource::collection($_metadatas);

            return response()->json($_metadatas, Response::HTTP_OK);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getMetadatas query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getMetadatas general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMetadata(Request $request)
    {
        try {
            $metadata = $this->metadataService->getMetadata($request->id);

            return response()->json($metadata, Response::HTTP_OK);
        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getMetadata model not found error', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'getMetadata query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getMetadata general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMetadataByKey(string $key)
    {
        try {
            $metadata = $this->metadataService->getMetadataByKey($key);

            return response()->json($metadata, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getCompanyByName model not found error', Response::HTTP_NOT_FOUND);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getCompanyByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getCompanyByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createMetadata(StoreSettingsMetadataRequest $request)
    {
        try {
            $metadata = $this->metadataService->createMetadata($request);

            return response()->json($metadata, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createMetadata query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createMetadata general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateMetadata(UpdateSettingsMetadataRequest $request, int $id)
    {
        try {
            $metadata = $this->metadataService->updateMetadata($request, $id);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateCompany model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteMetadatas(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->metadataService->deleteMetaDatas($request);

            return response()->json($deletedCount, Response::HTTP_OK);
        } catch(ValidationException $ex) {
            return $this->handleException($ex, 'deleteMetadatas validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteMetadatas query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteMetadatas general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteMetadata(GetSettingsMetadataRequest $request): JsonResponse
    {
        try {
            $metadata = $this->metadataService->deleteMetadata($request);

            return response()->json($metadata, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteMetadata model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deleteMetadata database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deleteMetadata general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreMetadata(GetSettingsMetadataRequest $request): JsonResponse
    {
        try {
            $metadata = $this->metadataService->restoreMetadata($request);

            return response()->json($metadata, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreMetadata model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreMetadata database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreMetadata general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function realDeleteMetadata(GetSettingsMetadataRequest $request): JsonResponse
    {
        try {
            $metadata = $this->metadataService->realDeleteMetadata($request->id);

            return response()->json($metadata, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeleteMetadata model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeleteMetadata database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeleteMetadata general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
