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

    public function __construct()
    {
        $this->middleware('can:company_settings list', ['only' => ['index', 'applySearch', 'getApplicationSettings', 'getApplicationSetting', 'getApplicatonSettingByName']]);
        $this->middleware('can:company_settings create', ['only' => ['createApplicationSetting']]);
        $this->middleware('can:company_settings edit', ['only' => ['updateApplicationSetting']]);
        $this->middleware('can:company_settings delete', ['only' => ['deleteApplicationSetting', 'deleteApplicationSettings']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles('company_settings');
        
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
    
    public function getSettings(Request $request, CacheService $cacheService): JsonResponse
    {
        try {
            $cacheKey = "company_settings_" . md5(json_encode($request->all()));

            $settings = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $settingsQuery = CompanySetting::search($request);
                return CompanySettingsResource::collection($settingsQuery->get());
            });

            return response()->json($settings, Response::HTTP_OK);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY_SETTINGS',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'getApplicationSettings general error',
                'route' => $request->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSetting(GetCompanySettingRequest $request, CacheService $cacheService)
    {
        try {
            $cacheKey = "company_setting_{$request->id}";
            
            $setting = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return CompanySetting::findOrFail($request->id);
            });
            
            return response()->json($setting, Response::HTTP_OK);
            
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'getCompanySetting error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Company Setting not found'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY_SETTING',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'getApplicationSetting general error',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSettingByKey(string $key, CacheService $cacheService): JsonResponse
    {
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
            ActivityController::logServerError($ex, [
                'context' => 'DB_ERROR_SETTING_BY_KEY',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'Database error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ActivityController::logServerError($ex, [
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

    /**
     * =============================================
     * 
     * =============================================
     * URL: POST /company_settings/key/default_language
     * Body: {
     *           "company_id": 1,
     *           "value": "fr",
     *           "active": 1
     *       }
     * 
     * @param StoreCompanysettingRequest $request
     * @param CacheService $cacheService
     * @return JsonResponse
     */
    public function createSetting(StoreCompanysettingRequest $request, string $key, CacheService $cacheService): JsonResponse
    {
        try{
            $metadata = \App\Models\SettingsMetadata::where('key', $key)->first();
            
            if( !$metadata ) {
                return response()->json(['error' => 'Invalid setting key'], Response::HTTP_NOT_FOUND);
            }
            
            $setting = CompanySetting::create([
                'company_id' => $request->input('company_id'),
                'key' => $key,
                'value' => $request->input('value'),
                'active' => $request->input('active', 1),
            ]);
            
            $cacheService->forgetAll($this->tag);

            return response()->json($setting, Response::HTTP_CREATED);
        }catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'CREATE_COMPANY_SETING_DATABASE_ERROR',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_company__setting_create_database_error'),
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ActivityController::logServerError($ex, [
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

    /**
     * =============================================
     * 
     * =============================================
     * URL: PUT /company_settings/key/default_language
     * Body: {
     *           "company_id": 1,
     *           "value": "es",
     *           "active": 0
     *       }
     * 
     * 
     * @param UpdateCompanySettingRequest $request
     * @param int $id
     * @param CacheService $cacheService
     * @return JsonResponse
     */
    public function updateSetting(UpdateCompanySettingRequest $request, string $key, CacheService $cacheService): JsonResponse
    {
        try {
            $setting = CompanySetting::where('company_id', '=', $request->input('company_id'))
                ->where('key', '=', $key)
                ->firstOrFail();
            $setting->update([
                'value' => $request->input('value'),
                'active' => $request->input('active', $setting->active),
            ]);
            $setting->refresh();

            $cacheService->forgetAll($this->tag);

            return response()->json($setting, Response::HTTP_OK);

        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'DB_ERROR_UPDATE_COMPANY_SETTING',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'COMPANY_SETTING_NOT_FOUND',
                'details' => $ex->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'DB_ERROR_COMPANY_SETTING',
                'route' => request()->path(),
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'DB_ERROR_COMPANY_SETTING',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ActivityController::logServerError($ex, [
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
