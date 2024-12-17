<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCompanySettingRequest;
use App\Http\Requests\StoreCompanysettingRequest;
use App\Http\Requests\UpdateCompanySettingRequest;
use App\Http\Resources\CompanySettingsResource;
use App\Models\CompanySetting;
use App\Repositories\CompanySettingRepository;
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
use Illuminate\Support\Facades\DB;

class CompanySettingController extends Controller
{
    use AuthorizesRequests,
        Functions;
    protected CompanySettingRepository $compSettingRepository;
    protected string $tag = 'company_settings';

    public function __construct(CompanySettingRepository $repository)
    {
        $this->compSettingRepository = $repository;

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
    
    public function getCompSettings(Request $request): JsonResponse {
        try {
            $cacheKey = "{$this->tag}_" . md5(json_encode($request->all()));

            $settings = $this->compSettingRepository->getCompSettings($request);

            return response()->json($settings, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCompanySettings query exception error', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCompanySettings general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSetting(GetCompanySettingRequest $request, CacheService $cacheService) {
        try {
            $cacheKey = "{$this->tag}_" . md5($request->id);
            
            $setting = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return CompanySetting::findOrFail($request->id);
            });
            
            return response()->json($setting, Response::HTTP_OK);
            
        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanySetting model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCompanySetting model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanySetting query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCompanySetting query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSetting general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
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
            $cacheKey = "{$this->tag}_" . md5($key);

            $setting = $cacheService->remember($this->tag, $cacheKey, function () use ($key) {
                return CompanySetting::where('key', '=', $key)->firstOrFail();
            });

            return response()->json($setting, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanySettingByKey model not found error',
                'params' => ['key' => $key],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => "getCompanySettingByKey model not found error"
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanySettingByKey query error',
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCompanySettingByKey query error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getCompanySettingByKey general error',
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            // JSON-választ küld vissza, jelezve, hogy váratlan hiba történt
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createSetting(StoreCompanysettingRequest $request, CacheService $cacheService): JsonResponse
    {
        try{
            $setting = CompanySetting::create($request->all());
            
            $cacheService->forgetAll($this->tag);

            return response()->json($setting, Response::HTTP_CREATED);
        }catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createCompanySetting query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createCompanySetting query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createCompanySetting general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'createCompanySetting general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSetting(UpdateCompanySettingRequest $request, int $id, CacheService $cacheService): JsonResponse {
        try {
            $setting = null;
            
            DB::transaction(function() use($request, $id, $cacheService, &$setting) {
                $setting = CompanySetting::findOrFail($id)->lockForUpdate();
                $setting->update($request->all());
                $setting->refresh();

                $cacheService->forgetAll($this->tag);
            });

            return response()->json($setting, Response::HTTP_OK);

        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateCompanySetting model not found error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateCompanySetting model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateCompanySetting query error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateCompanySetting query error',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateCompanySetting general error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateCompanySetting general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteApplicationSettings() {}
    
    public function deleteApplicationSetting() {}
}
