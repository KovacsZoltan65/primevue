<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAppSettingRequest;
use App\Http\Requests\StoreAppSettingRequest;
use App\Http\Requests\UpdateAppSettingRequest;
use App\Http\Resources\AppSettingsResource;
use App\Models\AppSetting;
use App\Repositories\AppSettingRepository;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\Functions;
use Exception;

class AppSettingController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected AppSettingRepository $appSettingRepository;

    protected string $tag = 'appSettings';

    public function __construct(AppSettingRepository $repository)
    {
        $this->appSettingRepository = $repository;

        $this->tag = AppSetting::getTag();
        
        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getAppSettings', 'getApplicatopnSetting', 'getApplicatopnSettingByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createApplicatopnSetting']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updateApplicatopnSetting']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deleteApplicatopnSetting', 'deleteAppSettings']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restoreAppSetting']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);

        return Inertia::render('Settings/AppSettings',[
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

    public function getAppSettings(Request $request): JsonResponse
    {
        try {
            $settings = $this->appSettingRepository->getAppSettings($request);

            return response()->json($settings, Response::HTTP_OK);
        } catch(QueryException $ex) {
            // Hiba továbbítása a frontend felé
            return $this->handleException($ex, 'getAppSettings query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            // Hiba továbbítása a frontend felé
            return $this->handleException($ex, 'getAppSettings general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getApptSetting(GetAppSettingRequest $request): JsonResponse
    {
        try {
            $setting = $this->appSettingRepository->getAppSetting($request->id);

            return response()->json($setting, Response::HTTP_OK);

        } catch( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'geApptSetting model not found error', Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            return $this->handleException($ex, 'geApptSetting query exception', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'geApptSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAppSettingByKey(string $key): JsonResponse
    {
        try {
            $setting = $this->appSettingRepository->getAppSettingByKey($key);

            return response()->json($setting, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getAppSettingByKey model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getAppSettingByKey query exception', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getAppSettingByKey general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createAppSetting(StoreAppSettingRequest $request): JsonResponse
    {
        try{
            $setting = $this->appSettingRepository->createAppSetting($request);

            return response()->json($setting, Response::HTTP_CREATED);
        }catch(QueryException $ex) {
            return $this->handleException($ex, 'createAppSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createAppSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateAppSetting(UpdateAppSettingRequest $request, int $id): JsonResponse
    {
        try {
            $setting = $this->appSettingRepository->updateAppSetting($request, $id);

            return response()->json($setting, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updateAppSetting model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updateAppSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updateAppSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteAppSettings(Request $request)
    {
        try {
            $deletedCount = $this->appSettingRepository->deleteAppSettings($request);
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteAppSettings model not found error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'deleteAppSettings query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'deleteAppSettings general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteAppSetting(GetAppSettingRequest $request)
    {
        try {
            $appSetting = $this->appSettingRepository->deleteAppSetting($request);

            return response()->json($appSetting, Response::HTTP_OK);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteAppSetting model not found exception', Response::HTTP_NOT_FOUND);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'deleteAppSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'deleteAppSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreAppSettings(GetAppSettingRequest $request): JsonResponse
    {
        try {
            $appSetting = $this->appSettingRepository->restoreAppSettings($request);

            return response()->json($appSetting, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreAppSettings model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreAppSettings query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreAppSettings general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function realDeleteCompSetting(Request $request): JsonResponse
    {
        try {
            $appSetting = $this->appSettingRepository->realDeleteSetting($request);
            
            return response()->json($appSetting, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeleteSetting model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeleteSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeleteSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
