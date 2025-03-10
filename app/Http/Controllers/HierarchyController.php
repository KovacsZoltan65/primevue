<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Hierarchy;
use App\Repositories\HierarchyRepository;
use App\Services\AppSettings\HierarchyService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response AS InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;
use App\Traits\Functions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HierarchyController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected HierarchyService $hierarchyService;

    protected string $tag = 'hierarchy';

    public function __construct(HierarchyService $hierarchyService)
    {
        $this->hierarchyService = $hierarchyService;

        $this->tag = Hierarchy::getTag();

        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getCompanies', 'getCompany', 'getCompanyByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createCompany']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updateCompany']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deleteCompany', 'deleteCompanies']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restoreCompany']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);

        return Inertia::render('Hierarchy/Index', [
            'can' => $roles,
        ]);
    }

    /**
     * Szülő entitást ad az alárendelt entitásokhoz.
     *
     * Ez a metódus lekéri a szülő entitás azonosítóját a kérésből és
     * szülő-gyermek kapcsolatot létesít a megadott szülő között
     * és gyermek entitások. Miután sikeresen csatlakoztatta a szülőt a gyermekhez,
     * naplózza a műveletet, és JSON-választ ad vissza a frissített utód entitással.
     *
     * @param Request $request A HTTP-kérelem objektum, amely tartalmazza a szülő_azonosítót.
     * @param int $childId Annak az utód entitásnak az azonosítója, amelyhez a szülő hozzáadódik.
     *
     * @return JsonResponse Sikert vagy kudarcot jelző JSON-válasz.
     *
     * @throws ModelNotFoundException Ha a szülő vagy gyermek entitás nem található.
     * @throws Exception Bármilyen egyéb hiba esetén a folyamat során.
     */
    public function addParent(Request $request, $childId): JsonResponse
    {
        try {
            $child = $this->hierarchyService->addParent($request, $childId);
            return response()->json([$child, Response::HTTP_OK]);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'addParent model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'addParent query error', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'addParent general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addChild(Request $request, $parentId): JsonResponse
    {
        try {

            $parent = $this->hierarchyService->addChild($request, $parentId);

            return response()->json($parent, Response::HTTP_OK);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'addChild model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'addChild query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'addChild general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getHierarchy($entityId): JsonResponse
    {
        try {
            $entity = Entity::with('parents', 'children')->findOrFail($entityId);

            return response()->json($entity);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getHierarchy model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getHierarchy query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'getHierarchy general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Gyermek eltávolítása
    public function removeChild(Request $request, $parentId): JsonResponse
    {
        try {
            $childId = $request->input('child_id');

            $count = $this->hierarchyService->removeChild($parentId, $childId);

            return response()->json($count, Response::HTTP_OK);
        } catch (ModelNotFoundException $ex) {
            return $this->handleException($ex, 'removeChild model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'removeChild query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $ex) {
            return $this->handleException($ex, 'removeChild general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // Nagyfőnök lekérése
    public function getBigBosses()
    {
        try {
            $bigBosses = $this->hierarchyService->getBigBosses();
        } catch(ModelNotFoundException $ex) {
            //
        } catch(QueryException $ex) {
            //
        } catch (Exception $ex) {
            //
        }
    }
    // Egy vezető beosztottjainak áthelyezése egy másik vezető alá
    public function transferSubordinates(Request $request, $fromManagerId)
    {
        try {
            $toManagerId = $request->input('to_manager_id');

            // Ellenőrizzük, hogy mindkét vezető létezik-e
            $fromManager = Entity::findOrFail($fromManagerId);
            $toManager = Entity::findOrFail($toManagerId);

            // Az "átmozgatandó" beosztottak
            $subordinates = $fromManager->children;

            // Áthelyezés a másik vezető alá
            foreach ($subordinates as $subordinate) {
                $fromManager->children()->detach($subordinate->id);
                $toManager->children()->attach($subordinate->id);
            }

            return response()->json([
                'message' => 'Subordinates successfully transferred.',
                'from_manager' => $fromManager->id,
                'to_manager' => $toManager->id,
                'subordinates' => $subordinates,
            ]);
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'transferSubordinates model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'transferSubordinates model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'transferSubordinates query error',
                'params' => ['id' => $request->id],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'transferSubordinates query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'transferSubordinates general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'transferSubordinates general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // Két vezető beosztottjainak kicserélése
    public function swapSubordinates(Request $request)
    {
        try {
            $managerAId = $request->input('manager_a_id');
            $managerBId = $request->input('manager_b_id');

            // Ellenőrizzük, hogy mindkét vezető létezik-e
            $managerA = Entity::findOrFail($managerAId);
            $managerB = Entity::findOrFail($managerBId);

            // Manager A és Manager B beosztottjai
            $subordinatesA = $managerA->children;
            $subordinatesB = $managerB->children;

            // Kapcsolatok megszüntetése
            $managerA->children()->detach();
            $managerB->children()->detach();

            // Beosztottak áthelyezése
            foreach ($subordinatesA as $subordinate) {
                $managerB->children()->attach($subordinate->id);
            }

            foreach ($subordinatesB as $subordinate) {
                $managerA->children()->attach($subordinate->id);
            }

            return response()->json([
                'message' => 'Subordinates successfully swapped.',
                'manager_a' => $managerA->id,
                'manager_b' => $managerB->id,
                'manager_a_new_subordinates' => $subordinatesB,
                'manager_b_new_subordinates' => $subordinatesA,
            ]);
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'swapSubordinates model not found error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'swapSubordinates model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'swapSubordinates query error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'swapSubordinates query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'swapSubordinates general error',
                'params' => ['request' => $request->all()],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'swapSubordinates general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Hierarchia épségének ellenőrzése
    public function validateHierarchy()
    {
        try {
            // 1. Nagyfőnökök ellenőrzése
            $bigBosses = Entity::whereDoesntHave('parents')->get();
            if ($bigBosses->isEmpty()) {
                return response()->json([
                    'error' => 'No big bosses found. Hierarchy is invalid.',
                ], 400);
            }

            // 2. Ciklusmentesség és teljes lefedettség ellenőrzése
            $entities = Entity::all();
            $unreachableEntities = collect();

            foreach ($entities as $entity) {
                if (!$this->canReachBigBoss($entity, $bigBosses)) {
                    $unreachableEntities->push($entity);
                }
            }

            if ($unreachableEntities->isNotEmpty()) {
                return response()->json([
                    'error' => 'Some entities cannot reach a big boss.',
                    'unreachable_entities' => $unreachableEntities,
                ], 400);
            }

            return response()->json([
                'message' => 'Hierarchy is valid.',
            ]);
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'getCompany model not found error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateHierarchy model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateHierarchy query error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getCompany query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateHierarchy general error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateHierarchy general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Eléri a nagyfőnököt?
    private function canReachBigBoss(Entity $entity, $bigBosses): bool
    {
        $current = $entity;

        // Felfelé lépegetés a hierarchiában
        while ($current) {
            if ($bigBosses->contains($current->id)) { // ID-t használunk az összehasonlításhoz, hogy minimalizáljuk az objektumok összehasonlítását
                return true;
            }
            $current = $current->parents()->first();
        }

        return false;
    }

    /*
     * =========================================
     * Ciklusok ellenőrzése
     * =========================================
     *
     *  1. Ha egy beosztott visszacsatol a saját láncában, az érvénytelen hierarchia.
     *  2. Ez megakadályozható a kapcsolatok létrehozásakor:
     *  2.1 Ellenőrizzük, hogy a beosztott nem már egy őse a vezetőnek.
     */

    /**
     * =========================================
     * Izolált entitások keresése
     * =========================================
     * Azok az entitások, amelyek nem kapcsolódnak semmihez (nincs szülőjük és gyerekük sem),
     * hibás állapotot jelezhetnek.
     */
    public function checkIsolatedEntities()
    {
        try {
            // Első szülő nélküli, és első gyermek nélküli entitások keresése egyszerre
            $isolatedEntities = Entity::doesntHave('parents')
                ->doesntHave('children')
                ->get();

            if ($isolatedEntities->isNotEmpty()) {
                return response()->json([
                    'error' => 'Isolated entities found in the hierarchy.',
                    'isolated_entities' => $isolatedEntities,
                ], 400);
            }

            return response()->json([
                'message' => 'No isolated entities found.',
            ]);
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkIsolatedEntities model not found error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkIsolatedEntities model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkIsolatedEntities query error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkIsolatedEntities query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkIsolatedEntities general error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkIsolatedEntities general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * =========================================
     * Több dolgozó szintjének meghatározása
     * =========================================
     */
    public function getEmployeesRoles(array $employeeIds): JsonResponse
    {
        try {
            // Lekérjük az összes kapcsolódó relációt egyszerre
            $relations = Hierarchy::whereIn('child_id', $employeeIds)
                ->orWhereIn('parent_id', $employeeIds)
                ->get();

            // Csoportosítás a kapcsolatok alapján
            $parentIds = $relations->pluck('parent_id')->unique();
            $childIds = $relations->pluck('child_id')->unique();

            // Az eredmények előkészítése
            $roles = [];

            foreach ($employeeIds as $employeeId) {
                if (!$parentIds->contains($employeeId) && $childIds->contains($employeeId)) {
                    $roles[$employeeId] = 'Nagyfőnök';
                } elseif ($parentIds->contains($employeeId) && $childIds->contains($employeeId)) {
                    $roles[$employeeId] = 'Vezető';
                } elseif ($parentIds->contains($employeeId) && !$childIds->contains($employeeId)) {
                    $roles[$employeeId] = 'Dolgozó';
                } else {
                    $roles[$employeeId] = 'Isolated';
                }
            }

            return response()->json([
                'roles' => $roles,
            ]);
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'getEmployeesRoles model not found error',
                'params' => ['$employeeIds' => $employeeIds],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getEmployeesRoles model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'getEmployeesRoles query error',
                'params' => ['$employeeIds' => $employeeIds],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getEmployeesRoles query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'getEmployeesRoles general error',
                'params' => ['$employeeIds' => $employeeIds],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getEmployeesRoles general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * =========================================
     * Dolgozói szint megállapítása
     * =========================================
     *
     * példa:   {
     *              "employee_id": 123,
     *              "role": "Vezető"
     *          }
     */
    public function getEmployeeRole($employeeId)
    {
        try {
            // Ellenőrizzük, hogy a dolgozó létezik-e
            $employee = Entity::findOrFail($employeeId);

            // Ellenőrizzük, hogy van-e felettese (parent_id)
            $hasParent = Hierarchy::where('child_id', $employeeId)->exists();

            // Ellenőrizzük, hogy van-e beosztottja (child_id)
            $hasChildren = Hierarchy::where('parent_id', $employeeId)->exists();

            // Meghatározzuk a szerepkört
            $role = $this->determineRole($hasParent, $hasChildren);

            return response()->json([
                'employee_id' => $employeeId,
                'role' => $role,
            ]);
        } catch (ModelNotFoundException $e) {
            ActivityController::logServerError($ex, [
                'context' => 'getEmployeeRole model not found error',
                'params' => ['employeeId' => $employeeId],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getEmployeeRole model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'getEmployeeRole query error',
                'params' => ['employeeId' => $employeeId],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getEmployeeRole query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'getEmployeeRole general error',
                'params' => ['employeeId' => $employeeId],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'getEmployeeRole general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function determineRole($hasParent, $hasChildren)
    {
        if (!$hasParent && $hasChildren) {
            return 'Nagyfőnök'; // Nincs felettese, de van beosztottja
        } elseif ($hasParent && $hasChildren) {
            return 'Vezető'; // Van felettese és vannak beosztottjai
        } elseif ($hasParent && !$hasChildren) {
            return 'Dolgozó'; // Csak felettese van, nincs beosztottja
        } else {
            return 'Isolated'; // Se felettese, se beosztottja (ritka, de lehetséges eset)
        }
    }

    /**
     * =========================================
     * ELLENŐRZÉSEK
     * =========================================
     */

    /**
     * =========================================
     * 1. Több gyökér (multiroot) ellenőrzése
     * =========================================
     * Mit jelent?: Ha a rendszer támogatja, hogy több "nagyfőnök" (gyökér
     *              szintű entitás) legyen, akkor biztosítani kell, hogy ezek
     *              ténylegesen függetlenek maradjanak egymástól.
     * Ellenőrzés:  Az egyes gyökerekből induló hierarchiák nem keresztezhetik
     *              egymást (egy entitás nem tartozhat egyszerre több gyökérhez).
     */
    public function checkMultipleRootsIntegrity()
    {
        try {
            $roots = Entity::whereDoesntHave('parents')->get();
            $visitedEntities = collect();

            foreach ($roots as $root) {
                $descendants = $this->getAllDescendants($root);
                foreach ($descendants as $descendant) {
                    if ($visitedEntities->contains($descendant->id)) {
                        return response()->json([
                            'error' => 'Hierarchy integrity issue: an entity belongs to multiple roots.',
                            'conflicting_entity' => $descendant,
                        ], 400);
                    }
                    $visitedEntities->push($descendant->id);
                }
            }

            return response()->json([
                'message' => 'Multiple roots integrity check passed.',
            ]);
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkMultipleRootsIntegrity model not found error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkMultipleRootsIntegrity model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkMultipleRootsIntegrity query error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkMultipleRootsIntegrity query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkMultipleRootsIntegrity general error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkMultipleRootsIntegrity general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getAllDescendants(Entity $entity): Collection
    {
        $descendants = collect();

        // Egy iterációs módszer használata a teljes leszármazott lista összegyűjtéséhez
        $queue = collect([$entity]);

        while ($queue->isNotEmpty()) {
            $current = $queue->shift(); // Az első elem kinyerése
            $descendants->push($current); // Hozzáadása a leszármazottakhoz

            // Az összes gyermek hozzáadása a sorhoz
            foreach ($current->children as $child) {
                $queue->push($child);
            }
        }

        return $descendants;
    }

    /**
     * =========================================
     * 2. Ciklusmentesség ellenőrzése valós idejű módosításokkor
     * =========================================
     * Mit jelent?: Minden módosítás (pl. új kapcsolat létrehozása vagy szülő
     *              változtatása) során ellenőrizni kell, hogy a változtatás
     *              nem hoz létre ciklust.
     * Ellenőrzés:  Amikor egy child_id értéket egy új parent_id alá helyeznek,
     *              biztosítani kell, hogy a parent_id ne tartozzon már a
     *              child_id leszármazottai közé.
     */
    public function validateNoCycles($parentId, $childId): bool
    {
        $current = Entity::find($parentId);

        while ($current) {
            if ($current->id === $childId) {
                return false; // Ciklus van
            }
            $current = $current->parents()->first(); // Egy szülő lekérése
        }

        return true;
    }


    /**
     * =========================================
     * 3. Elhagyott kapcsolatok ellenőrzése
     * =========================================
     * Mit jelent?: Biztosítani kell, hogy minden entitás vagy szülő, vagy
     *              gyermek kapcsolatban álljon, vagy valamilyen logikus
     *              állapotban legyen (pl. "izolált entitások" nem kívánt
     *              állapotban ne maradjanak).
     * Ellenőrzés:  Az összes entitásnak vagy gyökérként kell léteznie, vagy
     *              alá kell tartoznia egy hierarchiának.
     * Például:     Egy újonnan létrehozott entitás ne maradjon kapcsolatok
     *              nélkül.
     */
    public function checkOrphanedEntities()
    {
        try {
            // Összes entitás
            $allEntities = Entity::pluck('id')->toArray();

            // Entitások, amelyek szülőként jelennek meg
            $parents = Hierarchy::pluck('parent_id')->unique()->toArray();

            // Entitások, amelyek gyermekként jelennek meg
            $children = Hierarchy::pluck('child_id')->unique()->toArray();

            // Azok az entitások, amelyek sem szülőként, sem gyermekként nem szerepelnek
            $orphans = array_diff($allEntities, $parents, $children);

            if (!empty($orphans)) {
                return response()->json([
                    'error' => 'Orphaned entities found.',
                    'orphans' => Entity::whereIn('id', $orphans)->get(),
                ], 400);
            }

            return response()->json([
                'message' => 'No orphaned entities found.',
            ]);
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkOrphanedEntities model not found error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkOrphanedEntities model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkOrphanedEntities query error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkOrphanedEntities query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkOrphanedEntities general error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkOrphanedEntities general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * =========================================
     * 4. Körülhatárolt hierarchia a cégeken belül
     * =========================================
     * Mit jelent?: Ha a hierarchia cégekhez kötött, minden entitásnak
     *              ugyanahhoz a céghez kell tartoznia, mint a szülője.
     * Ellenőrzés:  Az entities táblában a company_id mező értéke a szülő és a
     *              gyermek esetében egyezzen.
     */
    public function validateCompanyIntegrity($parentId, $childId): JsonResponse
    {
        try {
            $parent = Entity::findOrFail($parentId);
            $child = Entity::findOrFail($childId);

            if ($parent->company_id !== $child->company_id) {
                return response()->json([
                    'error' => 'Parent and child must belong to the same company.',
                ], 400);
            }

            return response()->json([
                'message' => 'Company integrity validated successfully.',
            ]);
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateCompanyIntegrity model not found error',
                'params' => ['parentId' => $parentId, 'childId' => $childId],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateCompanyIntegrity model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch (ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateCompanyIntegrity query error',
                'params' => ['parentId' => $parentId, 'childId' => $childId],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateCompanyIntegrity query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateCompanyIntegrity general error',
                'params' => ['parentId' => $parentId, 'childId' => $childId],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateCompanyIntegrity general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * =========================================
     * 5. Kör teljes szint ellenőrzése
     * =========================================
     * Mit jelent?: Ha egy szülőhöz több gyermek tartozik, biztosítani kell,
     *              hogy a gyermekek azonos hierarchiai szinten legyenek.
     * Ellenőrzés:  Ez az ellenőrzés különösen fontos, ha a hierarchia
     *              szintekhez kötött (pl. különböző beosztási szintek).
     */
    public function checkHierarchyLevels()
    {
        try {
            $invalidRelations = Hierarchy::with(['parent', 'child'])
                ->get()
                ->filter(function ($relation) {
                    return abs($relation->parent->level - $relation->child->level) > 1;
                });

            if ($invalidRelations->isNotEmpty()) {
                return response()->json([
                    'error' => 'Invalid hierarchy levels detected.',
                    'invalid_relations' => $invalidRelations,
                ], 400);
            }

            return response()->json([
                'message' => 'All hierarchy levels are consistent.',
            ]);
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkHierarchyLevels model not found error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkHierarchyLevels model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkHierarchyLevels query error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkHierarchyLevels query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'checkHierarchyLevels general error',
                'params' => [],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'checkHierarchyLevels general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * =========================================
     * 6. Időbeli érvényesség ellenőrzése
     * =========================================
     * Mit jelent?: Az entities táblában lévő start_date és end_date mezők
     *              alapján biztosítani kell, hogy egy entitás csak érvényes
     *              időtartamon belül szerepeljen a hierarchiában.
     * Ellenőrzés:  Egy entitás csak akkor lehet egy másik szülője vagy gyermeke,
     *              ha az időtartamok átfedik egymást.
     */
    public function validateTemporalConsistency($parentId, $childId): bool
    {
        try {
            $parent = Entity::findOrFail($parentId);
            $child = Entity::findOrFail($childId);

            if ($child->start_date > $parent->end_date || $child->end_date < $parent->start_date) {
                throw new Exception('Temporal inconsistency: Parent and child active periods do not overlap.');
            }

            return true;
        } catch (ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateTemporalConsistency model not found error',
                'params' => ['parentId' => $parentId, 'childId' => $childId],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateTemporalConsistency model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateTemporalConsistency query error',
                'params' => ['parentId' => $parentId, 'childId' => $childId],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateTemporalConsistency query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateTemporalConsistency general error',
                'params' => ['parentId' => $parentId, 'childId' => $childId],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateTemporalConsistency general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * =========================================
     * 7. Adatkapcsolati redundancia ellenőrzése
     * =========================================
     * Mit jelent?: Ne legyenek redundáns (ismétlődő) kapcsolatok a táblában.
     * Ellenőrzés:  Egy parent_id és child_id pár csak egyszer szerepelhet.
     */
    public function validateUniqueRelationship($parentId, $childId): bool
    {
        try {
            $exists = Hierarchy::where('parent_id', $parentId)
                ->where('child_id', $childId)
                ->exists();

            if ($exists) {
                throw new Exception('This relationship already exists.');
            }

            return true;
        } catch(ModelNotFoundException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateUniqueRelationship model not found error',
                'params' => ['parentId' => $parentId, 'childId' => $childId],
                'route' => request()->path(),
                'type' => 'ModelNotFoundException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateUniqueRelationship model not found error'
            ], Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateUniqueRelationship query error',
                'params' => ['parentId' => $parentId, 'childId' => $childId],
                'route' => request()->path(),
                'type' => 'QueryException',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateUniqueRelationship query error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            ActivityController::logServerError($ex, [
                'context' => 'validateUniqueRelationship general error',
                'params' => ['parentId' => $parentId, 'childId' => $childId],
                'route' => request()->path(),
                'type' => 'Exception',
                'severity' => 'error',
            ]);

            return response()->json([
                'success' => APP_FALSE,
                'error' => 'validateUniqueRelationship general error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
