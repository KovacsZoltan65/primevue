<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCompanySettingRequest;
use App\Http\Requests\StoreCompanySettingRequest;
use App\Http\Requests\UpdateCompanySettingRequest;
use App\Models\CompanySetting;
use App\Repositories\CompanySettingRepository;
use App\Traits\Functions;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response;

class CompanySettingController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected CompanySettingRepository $compSettingRepository;
    protected string $tag = 'compSettings';

    public function __construct(CompanySettingRepository $repository)
    {
        $this->compSettingRepository = $repository;

        $tag = CompanySetting::getTag();
        $this->middleware("can:{$tag} list", ['only' => ['index', 'applySearch', 'getApplicationSettings', 'getApplicationSetting', 'getApplicatonSettingByName']]);
        $this->middleware("can:{$tag} create", ['only' => ['createApplicationSetting']]);
        $this->middleware("can:{$tag} edit", ['only' => ['updateApplicationSetting']]);
        $this->middleware("can:{$tag} delete", ['only' => ['deleteApplicationSetting', 'deleteApplicationSettings']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);

        return Inertia::render('Settings/CompanySettings', [
            'search' => request('search'),
            'can' => $roles
        ]);
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('key', 'like', "%{$search}%");
        });
    }

    public function getCompSettings(Request $request): JsonResponse
    {
        try {
            $settings = $this->compSettingRepository->getCompSettings($request);

            return response()->json($settings, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCompSettings query exception error', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCompSettings general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompSetting(GetCompanySettingRequest $request)
    {
        try {
            $setting = $this->compSettingRepository->getCompSetting($request->id);

            return response()->json($setting, Response::HTTP_OK);

        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getCompanySetting model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCompanySetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getApplicationSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompSettingByKey(string $key): JsonResponse
    {
        try {
            $setting = $this->compSettingRepository->getCompSettingByKey($key);

            return response()->json($setting, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getCompSettingByKey model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCompSettingByKey query exception', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCompSettingByKey general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCompSetting(StoreCompanySettingRequest $request): JsonResponse{
        try{
            $setting = $this->compSettingRepository->createCompSetting($request);

            return response()->json($setting, Response::HTTP_CREATED);
        }catch(QueryException $ex) {
            return $this->handleException($ex, 'createCompSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createCompSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCompSetting(UpdateCompanySettingRequest $request, int $id): JsonResponse {
        try {
            $setting = $this->compSettingRepository->updateCompSetting($request, $id);

            return response()->json($setting, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateCompSetting model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateCompSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateCompSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCompSettings(Request $request): JsonResponse
    {
        try {
            $deletedCount = $this->compSettingRepository->deleteCompSettings($request);
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteAppSettings model not found error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'deleteAppSettings query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'deleteAppSettings general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCompSetting(GetCompanySettingRequest $request): JsonResponse
    {
        try {
            $appSetting = $this->compSettingRepository->deleteCompSetting($request);

            return response()->json($appSetting, Response::HTTP_OK);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteCompSetting model not found exception', Response::HTTP_NOT_FOUND);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'deleteCompSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'deleteCompSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreCompSetting(GetCompanySettingRequest $request): JsonResponse {
        try {
            $appSetting = $this->compSettingRepository->restoreCompSetting($request);

            return response()->json($appSetting, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreCompSetting model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreCompSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreCompSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
