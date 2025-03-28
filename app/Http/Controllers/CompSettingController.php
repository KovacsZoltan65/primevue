<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCompSettingRequest;
use App\Http\Requests\StoreCompSettingRequest;
use App\Http\Requests\UpdateCompSettingRequest;
use App\Models\CompSetting;
use App\Repositories\CompSettingRepository;
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

class CompSettingController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected CompSettingController $compSettingController;

    protected string $tag = 'compSettings';

    public function __construct(CompSettingController $compSettingController)
    {
        $this->compSettingController = $compSettingController;

        $this->tag = CompSetting::getTag();

        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getCompSettings', 'getCompSetting', 'getCompSettingByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createCompSetting']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updateCompSetting']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deleteCompSetting', 'deleteCompSettings']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restoreCompSetting']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);

        return Inertia::render('Settings/CompSettings', [
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
            $settings = $this->compSettingController->getCompSettings($request);

            return response()->json($settings, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCompSettings query exception error', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCompSettings general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompSetting(GetCompSettingRequest $request)
    {
        try {
            $setting = $this->compSettingController->getCompSetting($request->id);

            return response()->json($setting, Response::HTTP_OK);

        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getCompSetting model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCompSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getAppSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompSettingByKey(string $key): JsonResponse
    {
        try {
            $setting = $this->compSettingController->getCompSettingByKey($key);

            return response()->json($setting, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getCompSettingByKey model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getCompSettingByKey query exception', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getCompSettingByKey general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCompSetting(StoreCompSettingRequest $request): JsonResponse{
        try{
            $setting = $this->compSettingController->createCompSetting($request);

            return response()->json($setting, Response::HTTP_CREATED);
        }catch(QueryException $ex) {
            return $this->handleException($ex, 'createCompSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createCompSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCompSetting(UpdateCompSettingRequest $request, int $id): JsonResponse {
        try {
            $setting = $this->compSettingController->updateCompSetting($request, $id);

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
            $deletedCount = $this->compSettingController->deleteCompSettings($request);
            return response()->json($deletedCount, Response::HTTP_OK);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteAppSettings model not found error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'deleteAppSettings query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'deleteAppSettings general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCompSetting(GetCompSettingRequest $request): JsonResponse
    {
        try {
            $appSetting = $this->compSettingController->deleteCompSetting($request);

            return response()->json($appSetting, Response::HTTP_OK);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deleteCompSetting model not found exception', Response::HTTP_NOT_FOUND);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'deleteCompSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'deleteCompSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restoreCompSetting(GetCompSettingRequest $request): JsonResponse {
        try {
            $compSetting = $this->compSettingController->restoreCompSetting($request);

            return response()->json($compSetting, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restoreCompSetting model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restoreCompSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restoreCompSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function realDeleteCompSetting(Request $request): JsonResponse
    {
        try {
            $compSetting = $this->compSettingController->realDeleteSetting($request);

            return response()->json($compSetting, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeleteSetting model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeleteSetting query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeleteSetting general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
