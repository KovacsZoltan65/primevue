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
    
    protected string $tag = 'app_settings';

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
            $cacheKey = "{$this->tag}_" . md5(json_encode($request->all()));

            $settings = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $settingsQuery = ApplicationSetting::search($request);
                return ApplicationSettingsResource::collection($settingsQuery->get());
            });

            return response()->json($settings, Response::HTTP_OK);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSettings query error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getSettings query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch( Exception $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSettings general error',
                'params' => ['request' => $request->all()],
                'route' => $request->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSetting(GetApplicationSettingRequest $request, CacheService $cacheService) {
        try {
            $cacheKey = "{$this->tag}_" . md5($request->id);
            
            $setting = $cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                return ApplicationSetting::findOrFail($request->id);
            });
            
            return response()->json($setting, Response::HTTP_OK);
            
        } catch( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSetting model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getApplicationSetting model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch( QueryException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSetting query exception',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getApplicationSetting query exception'
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
                'error' => 'getApplicationSetting general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSettingByKey(string $key, CacheService $cacheService): JsonResponse {
        try {
            $cacheKey = "{$this->tag}_" . md5($key);

            $setting = $cacheService->remember($this->tag, $cacheKey, function () use ($key) {
                return ApplicationSetting::where('key', '=', $key)->firstOrFail();
            });

            return response()->json($setting, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSettingByKey model not found error',
                'params' => ['key' => $key],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);
            
            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getApplicationSettingByKey model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getApplicationSettingByKey query exception',
                'params' => ['key' => $key],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getApplicationByKey query exception',
                'details' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'getSettingByKey general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
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
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => __('command_application__setting_create_database_error'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'createApplicationSetting general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'An unexpected error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSetting(UpdateApplicationSettingRequest $request, int $id, CacheService $cacheService): JsonResponse {
        try {
            $setting = null;
            
            \DB::transaction(function() use($request, $id, $cacheService, &$setting) {
                $setting = ApplicationSetting::findOrFail($id);
                $setting->update($request->all())->lockForUpdate();
                $setting->refresh();

                $cacheService->forgetAll($this->tag);
            });

            return response()->json($setting, Response::HTTP_OK);

        } catch(ModelNotFoundException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateSetting model not found error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateSetting model not found error',
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateSetting query error',
                'params' => ['id' => $id, 'request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateSetting query error',
                'details' => $ex->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            ErrorController::logServerError($ex, [
                'context' => 'updateApplicationSetting general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'updateApplicationSetting general error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteApplicationSettings() {}
    
    public function deleteApplicationSetting() {}
}
