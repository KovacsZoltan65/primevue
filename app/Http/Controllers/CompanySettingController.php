<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCompanySettingRequest;
use App\Http\Requests\StoreCompanysettingRequest;
use App\Http\Requests\UpdateCompanySettingRequest;
use App\Http\Resources\CompanySettingsResource;
use App\Models\CompanySetting;
use App\Services\CacheService;
use App\Traits\Functions;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\SettingService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompanySettingController extends Controller
{
    use AuthorizesRequests,
        Functions;
    protected string $tag = 'company_settings';

    public function __construct() {
        $this->middleware('can:company_settings list', ['only' => ['index', 'applySearch', 'getApplicationSettings', 'getApplicationSetting', 'getApplicatonSettingByName']]);
        $this->middleware('can:company_settings create', ['only' => ['createApplicationSetting']]);
        $this->middleware('can:company_settings edit', ['only' => ['updateApplicationSetting']]);
        $this->middleware('can:company_settings delete', ['only' => ['deleteApplicationSetting', 'deleteApplicationSettings']]);
    }

    public function index(Request $request): InertiaResponse {
        $roles = $this->getUserRoles('company_settings');
        
        return Inertia::render('Settings/CompanySettings', [
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
            $cacheKey = "company_settings_" . md5(json_encode($request->all()));

            $settings = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $settingsQuery = CompanySetting::search($request);
                return CompanySettingsResource::collection($settingsQuery->get());
            });

            return response()->json($settings, Response::HTTP_OK);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY_SETTINGS',
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

    public function getSetting(GetCompanySettingRequest $request, CacheService $cacheService) {
        try {
            $cacheKey = "company_setting_{$request->id}";
            
            $setting = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return CompanySetting::findOrFail($request->id);
            });
            
            return response()->json($setting, Response::HTTP_OK);
            
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanySetting error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Company Setting not found'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY_SETTING',
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
            $cacheKey = "company_key_" . md5($key);

            $setting = $cacheService->remember($this->tag, $cacheKey, function () use ($key) {
                return CompanySetting::where('key', '=', $key)->first();
            });

            if(!$setting) {
                return response()->json([
                    'success' => APP_FALSE,
                    'error' => 'Company Setting not found'
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

    public function createSetting(StoreCompanysettingRequest $request, CacheService $cacheService): JsonResponse{
        try{
            $setting = CompanySetting::create($request->all());
            $cacheService->forgetAll($this->tag);

            return response()->json($setting, Response::HTTP_CREATED);
        }catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'CREATE_COMPANY_SETING_DATABASE_ERROR',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_company__setting_create_database_error'),
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createCompanySetting general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSetting(UpdateCompanySettingRequest $request, int $id, CacheService $cacheService): JsonResponse {
        try {
            $setting = CompanySetting::findOrFail($id);
            $setting->update($request->all());
            $setting->refresh();

            $cacheService->forgetAll($this->tag);

            return response()->json($setting, Response::HTTP_OK);

        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_COMPANY_SETTING',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'COMPANY_SETTING_NOT_FOUND',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY_SETTING',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_COMPANY_SETTING',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateCompanySetting general error',
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
