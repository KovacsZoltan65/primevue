<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Repositories\ActivityRepository;
use App\Traits\Functions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
//use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\Response;
use TheSeer\Tokenizer\Exception;
use Throwable;

class ActivityController extends Controller
{
    use AuthorizesRequests,
        Functions;
    
    protected static ActivityRepository $activityRepository;
    
    protected string $tag = '';
    
    public function __construct(ActivityRepository $repository)
    {
        self::$activityRepository = $repository;
        
        $this->tag = Activity::getTag();
        
        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getActivities', 'getActivity']]);
        //$this->middleware("can:{$this->tag} create", ['only' => ['createActivity']]);
        //$this->middleware("can:{$this->tag} edit", ['only' => ['updateActivity']]);
        //$this->middleware("can:{$this->tag} delete", ['only' => ['deleteActivity', 'deleteActivities']]);
        //$this->middleware("can:{$this->tag} restore", ['only' => ['restoreActivity']]);
    }
    
    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);

        return Inertia::render('Activities/Index', [
           'search' => $request->input('search'),
            'can' => $roles,
        ]);
    }
    
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function ($query, string $search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    public function getActivities(Request $request): JsonResponse
    {
        try {
            $_activities = self::$activityRepository->getActivities($request);
            $activities = ActivityResource::collection($_activities);
            
            return response()->json($activities, Response::HTTP_OK);
        } catch (QueryException $ex) {
            return $this->handleException($ex, 'getActivities query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getActivities general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getActivity(GetActivityRequest $request): JsonResponse
    {
        try {
            $activity = self::$activityRepository->getActivity($request->id);
            
            return response()->json($activity, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getActivity model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getActivity query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getActivity general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * ==========================================
     * 1. Hibák listázása dátumszűrővel:
     *      GET /error-logs?date_from=2024-11-01&date_to=2024-11-20
     * 2. Egy adott hiba megtekintése:
     *      GET /error-logs/123
     * 3. Hiba törlése:
     *      DELETE /error-logs/123
     * ==========================================
     */

    public static function logServerError(Throwable $error, array $additionalData = []): JsonResponse
    {
        $return_array = self::$activityRepository->logServerError($error, $additionalData);

        return response()->json($return_array, Response::HTTP_OK);
    }

    public static function logClientError(Request $request): JsonResponse
    {
        $return_array = self::$activityRepository->logClientError($request);

        return response()->json($return_array, Response::HTTP_OK);
    }

    public static function logClientValidationError(Request $request): JsonResponse
    {
        $errorId = self::$activityRepository->logClientValidationError($request);

        return response()->json([
            'success' => true,
            'message' => 'Validation error logged.',
            'errorId' => $errorId,
        ], Response::HTTP_OK);
    }

    public static function logServerValidationError(ValidationException $ex, Request $request): JsonResponse
    {
        $result = self::$activityRepository->logServerValidationError($ex, $request);
        
        return response()->json($result);
    }

    /**
     * Egy adott hiba megtekintése
     *
     * A show metódus fogja visszaadni a hibát, amelyet az adatbázisból
     * lekérünk a findOrFail() metódussal. Az inertia() függvénnyel egy új oldalt
     * renderelünk a hibával.
     *
     * @param int $id A hiba azonosítója
     * @return \Inertia\Response
     */
    public function show(int $id): InertiaResponse
    {
        // Lekérjük a hibát az adatbázisból
        // A findOrFail() metódus egy kivételt dob, ha a hiba nem található
        $log = Activity::findOrFail($id);

        // Egy hiba megtekintéséhez a show metódus fogja visszaadni a hibát
        // Az inertia() függvénnyel egy új oldalt renderelünk a hibával
        // A hibát egy reaktív hivatkozásban kapjuk meg, amelyet az adatbázisból
        // lekérünk a findOrFail() metódussal.
        return inertia('ErrorLogs/Show', [
            'log' => $log,
        ]);
    }

    /**
     * Törli a megadott hibát az adatbázisból.
     *
     * A destroy metódus fogja visszaadni a hibát, amelyet az adatbázisból
     * lekérünk a findOrFail() metódussal. A hiba törlését a delete() metódus
     * végzi, ami egy "hard delete"-t jelent. A hiba törlését követően a
     * rendszer átirányít a hibalista oldalra, ahol a felhasználó
     * értesülhet a hiba törléséről.
     *
     * @param int $id A hiba azonosítója
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $log = Activity::findOrFail($id);

        // Példa: a törlés helyett egy "deaktiválás" lehetőséget biztosítunk
        $log->delete(); // Ezt "soft delete"-ként is lehetne implementálni.

        return redirect()->route('error-logs.index')->with('success', 'Log entry deleted.');
    }

    public function getErrorById(string $errorId): JsonResponse
    {
        $result = self::$activityRepository->getErrorById($errorId);
        
        return response()->json($result['array'], $result['response']);
    }

    public function getErrorByUniqueId(string $uniqueErrorId): JsonResponse
    {
        $result = self::$activityRepository->getErrorById($uniqueErrorId);
        
        return response()->json($result['array'], $result['response']);
    }
}
