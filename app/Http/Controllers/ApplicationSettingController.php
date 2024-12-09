<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetApplicationSettingRequest;
use App\Http\Requests\StoreApplicationSettingRequest;
use App\Http\Requests\UpdateApplicationSettingRequest;
use App\Http\Resources\ApplicationSettingsResource;
use App\Models\ApplicationSetting;
use App\Traits\Functions;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\CacheService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApplicationSettingController extends Controller
{
    use AuthorizesRequests,
        Functions;
    protected string $tag = 'application_settings';

    public function __construct() {
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
    
    public function getSettings(Request $request, CacheService $cacheService): JsonResponse {
        try {
            $cacheKey = "application_settings_" . md5(json_encode($request->all()));

            $settings = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $settingsQuery = ApplicationSetting::search($request);
                return ApplicationSettingsResource::collection($settingsQuery->get());
            });

            return response()->json($settings, Response::HTTP_OK);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_APPLICATION_SETTINGS',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSettings general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSetting(GetApplicationSettingRequest $request, CacheService $cacheService) {
        try {
            $cacheKey = "application_setting_{$request->id}";
            
            $setting = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return ApplicationSetting::findOrFail($request->id);
            });
            
            return response()->json($setting, Response::HTTP_OK);
            
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSetting error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Application Setting not found'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_APPLICATION_SETTING',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSetting general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSettingByKey(string $key, CacheService $cacheService): JsonResponse {
        try {
            $cacheKey = "application_key_" . md5($key);

            $setting = $cacheService->remember($this->tag, $cacheKey, function () use ($key) {
                return ApplicationSetting::where('key', '=', $key)->first();
            });

            if(!$setting) {
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'Application Setting not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json($setting, Response::HTTP_OK);

        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_SETTING_BY_KEY',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSettingByKey general error',
                'route' => request()->path(),
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createSetting(StoreApplicationSettingRequest $request, CacheService $cacheService): JsonResponse{
        try{
            $setting = ApplicationSetting::create($request->all());
            $cacheService->forgetAll($this->tag);

            return response()->json($setting, Response::HTTP_CREATED);
        }catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_APPLICATION_SETING_DATABASE_ERROR',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_application__setting_create_database_error'),
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createApplicationSetting general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSetting(UpdateApplicationSettingRequest $request, int $id, CacheService $cacheService): JsonResponse {
        try {
            $setting = ApplicationSetting::findOrFail($id);
            $setting->update($request->all());
            $setting->refresh();

            $cacheService->forgetAll($this->tag);

            return response()->json($setting, Response::HTTP_OK);

        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_APPLICATION_SETTING',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'APPLICATION_SETTING_NOT_FOUND',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_APPLICATION_SETTING',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_APPLICATION_SETTING',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateApplicationSetting general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteApplicationSettings() {}
    
    public function deleteApplicationSetting() {}
}
