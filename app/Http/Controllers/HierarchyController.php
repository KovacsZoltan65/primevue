<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Hierarchy;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HierarchyController extends Controller
{
    // Szülö hozzáadása
    public function addParent(Request $request, $childId): JsonResponse
    {
        try {
            $parentId = $request->input('parent_id');
            $parent = Entity::findOrFail($parentId);
            $child = Entity::findOrFail($childId);

            // Add parent-child relationship
            $child->parents()->attach($parent);

            activity()
                ->performedOn($child)
                ->causedBy(auth()->user())
                ->withProperties(['parent_id' => $parentId])
                ->log('Parent added to entity.');

            return response()->json([
                'message' => 'Parent added successfully.',
                'child' => $child->load('parents'),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Entity not found.',
                'details' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            Log::error('Error adding parent to entity', [
                'child_id' => $childId,
                'parent_id' => $request->input('parent_id'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not add parent.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    // Gyermek hozzáadása
    public function addChild(Request $request, $parentId)
    {
        try {
            $childId = $request->input('child_id');
            $parent = Entity::findOrFail($parentId);
            $child = Entity::findOrFail($childId);

            // Add child relationship
            $parent->children()->attach($child);

            activity()
                ->performedOn($parent)
                ->causedBy(auth()->user())
                ->withProperties(['child_id' => $childId])
                ->log('Child added to entity.');

            return response()->json([
                'message' => 'Child added successfully.',
                'parent' => $parent->load('children'),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Entity not found.',
                'details' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            Log::error('Error adding child to entity', [
                'parent_id' => $parentId,
                'child_id' => $request->input('child_id'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not add child.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    // Hierarchia lekérdezése
    public function getHierarchy($entityId)
    {
        try {
            $entity = Entity::with('parents', 'children')->findOrFail($entityId);

            return response()->json($entity);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Entity not found.',
                'details' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('Error fetching entity hierarchy', [
                'entity_id' => $entityId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not fetch hierarchy.',
                'details' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }
    // Gyermek eltávolítása
    public function removeChild(Request $request, $parentId)
    {
        try {
            $childId = $request->input('child_id');
            $parent = Entity::findOrFail($parentId);
            $child = Entity::findOrFail($childId);

            // Remove child relationship
            $parent->children()->detach($child);

            activity()
                ->performedOn($parent)
                ->causedBy(auth()->user())
                ->withProperties(['child_id' => $childId])
                ->log('Child removed from entity.');

            return response()->json([
                'message' => 'Child removed successfully.',
                'parent' => $parent->load('children'),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Parent or Child entity not found.',
                'details' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('Error removing child from entity', [
                'parent_id' => $parentId,
                'child_id' => $request->input('child_id'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not remove child.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    // Nagyfőnök lekérése
    public function getBigBosses()
    {
        try {
            // Azok az entitások, amelyek nem szerepelnek parent_id-ként a kapcsolati táblában
            $bigBosses = Entity::whereDoesntHave('parents')->get();

            if ($bigBosses->isEmpty()) {
                return response()->json([
                    'message' => 'No big bosses found.',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'Big bosses retrieved successfully.',
                'big_bosses' => $bigBosses,
            ]);
        } catch (Exception $e) {
            Log::error('Error retrieving big bosses', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not retrieve big bosses.',
                'details' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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

            activity()
                ->causedBy(auth()->user())
                ->performedOn($fromManager)
                ->withProperties(['to_manager_id' => $toManagerId, 'subordinates' => $subordinates->pluck('id')])
                ->log('Subordinates transferred to another manager.');

            return response()->json([
                'message' => 'Subordinates successfully transferred.',
                'from_manager' => $fromManager->id,
                'to_manager' => $toManager->id,
                'subordinates' => $subordinates,
            ]);
        } catch (Exception $e) {
            Log::error('Error transferring subordinates', [
                'from_manager_id' => $fromManagerId,
                'to_manager_id' => $request->input('to_manager_id'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not transfer subordinates.',
                'details' => $e->getMessage(),
            ], 500);
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

            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'manager_a_id' => $managerAId,
                    'manager_b_id' => $managerBId,
                    'subordinates_swapped' => [
                        'manager_a_to_b' => $subordinatesA->pluck('id'),
                        'manager_b_to_a' => $subordinatesB->pluck('id'),
                    ],
                ])
                ->log('Subordinates swapped between two managers.');

            return response()->json([
                'message' => 'Subordinates successfully swapped.',
                'manager_a' => $managerA->id,
                'manager_b' => $managerB->id,
                'manager_a_new_subordinates' => $subordinatesB,
                'manager_b_new_subordinates' => $subordinatesA,
            ]);
        } catch (Exception $e) {
            Log::error('Error swapping subordinates', [
                'manager_a_id' => $request->input('manager_a_id'),
                'manager_b_id' => $request->input('manager_b_id'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not swap subordinates.',
                'details' => $e->getMessage(),
            ], 500);
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
        } catch (Exception $e) {
            Log::error('Error validating hierarchy', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not validate hierarchy.',
                'details' => $e->getMessage(),
            ], 500);
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
        } catch (Exception $e) {
            Log::error('Error checking for isolated entities', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not check for isolated entities.',
                'details' => $e->getMessage(),
            ], 500);
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
        } catch (Exception $e) {
            Log::error('Error determining employees roles', [
                'error' => $e->getMessage(),
                'employee_ids' => $employeeIds,
            ]);

            return response()->json([
                'error' => 'Could not determine employees roles.',
                'details' => $e->getMessage(),
            ], 500);
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
            Log::error("Employee not found", ['employee_id' => $employeeId]);

            return response()->json([
                'error' => 'Employee not found',
            ], 404);
        } catch (Exception $e) {
            Log::error('Error determining employee role', [
                'error' => $e->getMessage(),
                'employee_id' => $employeeId,
            ]);

            return response()->json([
                'error' => 'Could not determine employee role.',
                'details' => $e->getMessage(),
            ], 500);
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
        } catch (Exception $e) {
            Log::error('Error checking multiple roots integrity', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not check multiple roots integrity.',
                'details' => $e->getMessage(),
            ], 500);
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
        } catch (Exception $e) {
            Log::error('Error checking orphaned entities', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not check for orphaned entities.',
                'details' => $e->getMessage(),
            ], 500);
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
        } catch (ModelNotFoundException $e) {
            Log::error('Entity not found', ['parent_id' => $parentId, 'child_id' => $childId]);
            return response()->json([
                'error' => 'Entity not found.',
            ], 404);
        } catch (Exception $e) {
            Log::error('Error validating company integrity', [
                'error' => $e->getMessage(),
                'parent_id' => $parentId,
                'child_id' => $childId,
            ]);

            return response()->json([
                'error' => 'Could not validate company integrity.',
                'details' => $e->getMessage(),
            ], 500);
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
        } catch (Exception $e) {
            Log::error('Error checking hierarchy levels', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Could not check hierarchy levels.',
                'details' => $e->getMessage(),
            ], 500);
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
        } catch (ModelNotFoundException $e) {
            Log::error("Entity not found", ['parent_id' => $parentId, 'child_id' => $childId]);
            throw new Exception('Entity not found.');
        } catch (Exception $e) {
            Log::error('Error validating temporal consistency', [
                'error' => $e->getMessage(),
                'parent_id' => $parentId,
                'child_id' => $childId,
            ]);

            throw new Exception('Could not validate temporal consistency.', 0, $e);
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
        } catch (Exception $e) {
            Log::error('Error validating unique relationship', [
                'error' => $e->getMessage(),
                'parent_id' => $parentId,
                'child_id' => $childId,
            ]);

            throw new Exception('Could not validate unique relationship.', 0, $e);
        }
    }

    
}
