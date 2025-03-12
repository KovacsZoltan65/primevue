<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingsMetadataRequest;
use App\Http\Requests\UpdateSettingsMetadataRequest;
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

    protected SettingsMetadataService $settingsMetadataService;

    protected string $tag = '';

    public function __construct(SettingsMetadataService $settingsMetadataService) {
        $this->settingsMetadataService = $settingsMetadataService;
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
            $metadata = $this->settingsMetadataService->getActiveMetadata();

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
            $_metadatas = $this->settingsMetadataService->getMetadatas($request);
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
            $metadata = $this->settingsMetadataService->getMetadata($request->id);

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
            $metadata = $this->settingsMetadataService->getMetadataByKey($key);

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
            $metadata = $this->settingsMetadataService->createMetadata($request);

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
            $metadata = $this->settingsMetadataService->updateMetadata($request, $id);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateCompany model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateCompany query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateCompany general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $metadata = SettingsMetadata::where('id', $id)->firstOrFail();

        $validated = $request->validate();

        $metadata->update($validated);
        return response()->json($metadata);
    }

    public function deleteMetadata(int $id)
    {
        $metadata = SettingsMetadata::where('id', $id)->firstOrFail();
        $metadata->delete();

        return response()->json(['message' => 'Metadata deactivated successfully']);
    }
}
