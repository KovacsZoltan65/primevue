<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetApplicationSettingRequest;
use App\Http\Requests\StoreApplicationSettingRequest;
use App\Http\Requests\UpdateApplicationSettingRequest;
use App\Http\Resources\ApplicationSettingsResource;
use App\Models\ApplicationSetting;
use App\Repositories\ApplicationSettingRepository;
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

class ApplicationSettingController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected $appSettingRepository;

    protected string $tag = 'appSettings';

    public function __construct(ApplicationSettingRepository $repository)
    {
        $this->appSettingRepository = $repository;

        $this->middleware('can:application_settings list', ['only' => ['index', 'applySearch', 'getApplicationSettings', 'getApplicatopnSetting', 'getApplicatopnSettingByName']]);
        $this->middleware('can:application_settings create', ['only' => ['createApplicatopnSetting']]);
        $this->middleware('can:application_settings edit', ['only' => ['updateApplicatopnSetting']]);
        $this->middleware('can:application_settings delete', ['only' => ['deleteApplicatopnSetting', 'deleteApplicationSettings']]);
    }

    public function index(Request $request): InertiaResponse {
        $roles = $this->getUserRoles('application_settings');

        return Inertia::render('Settings/ApplicationSettings',[
            'search' => request('search'),
            'can' => $roles
        ]);
    }

    public function applySearch(Builder $query, string $search): Builder {
        return $query->when($search, function ($query, string $search) {
            $query->where('key', 'like', "%{$search}%");
        });
    }

    public function getAppSettings(Request $request): JsonResponse {
        try {
            $settings = $this->appSettingRepository->getAppSettings($request);

            return response()->json($settings, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getAppSettings query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch( Exception $ex ) {
            return $this->handleException($ex, 'getAppSettings general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getApptSetting(GetApplicationSettingRequest $request): JsonResponse
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
            return $this->handleException($ex, 'getApplicationSettingByKey model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getAppSettingByKey query exception', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getAppSettingByKey general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createAppSetting(StoreApplicationSettingRequest $request): JsonResponse{
        try{
            $setting = $this->appSettingRepository->createAppSetting($request);

            return response()->json($setting, Response::HTTP_CREATED);
        }catch(QueryException $ex) {
            return $this->handleException($ex, 'createAppSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createAppSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateAppSetting(UpdateApplicationSettingRequest $request, int $id): JsonResponse {
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

    public function deleteAppSetting(GetApplicationSettingRequest $request)
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
    
    public function restoreAppSettings(GetApplicationSettingRequest $request): JsonResponse
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
}
