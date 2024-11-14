<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends Controller
{
    public function __construct() {
        //
    }

    public function index(Request $request)
    {
        $companies = \App\Models\Company::select('id', 'name')
            ->orderBy('name')->get()->toArray();
        
        return Inertia::render('Entity/Index', [
            'search' => $request->get('search'),
            'companies' => $companies
        ]);
    }

    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            // Adjon hozzá egy where záradékot a lekérdezéshez azzal a feltétellel, hogy a
            // az ország nevének tartalmaznia kell a keresési lekérdezést
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getEntities(Request $request)
    {
        $entityQuery = Entity::Search($request);
        $entities = EntityResource::collection($entityQuery->get());

        return $entities;
    }

    public function getEntity(int $id)
    {
        $entity = Entity::find($id);

        return response()->json($entity, Response::HTTP_OK);
    }

    public function getEntityByName(string $name)
    {
        $entity = Entity::where('name', $name)->first();

        return response()->json($entity, Response::HTTP_OK);
    }

    public function createEntity(StoreEntityRequest $request)
    {
        //\Log::info('$request->all(): ', $request->all());
        $entity = Entity::create($request->all());
\Log::info('$entity' . print_r($entity, true));
        return response()->json($entity, Response::HTTP_OK);
    }

    public function updateEntity(UpdateEntityRequest $request, int $id)
    {
        $old_entity = Entity::find($id);

        $success = $old_entity->update($request->all());

        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    public function deleteEntity(int $id)
    {
        $entity = Entity::find($id);
        $success = $entity->delete();

        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    /**
     * Több entitást töröl a kérésből a megadott azonosítók alapján.
     *
     * A függvény kivonja az entitásazonosítókat a kérésből, ellenőrzi azok érvényességét,
     * és törli a megfelelő entitásokat az adatbázisból.
     *
     * @param Request $request A törölni kívánt azonosítókkal rendelkező „entitások” tömböt tartalmazó HTTP-kérés.
     * @return \Illuminate\Http\JsonResponse JSON-válasz, amely jelzi a sikeres állapotot és a törölt entitások számát.
     */
    public function deleteEntities(Request $request)
    {
        // Gyűjtsük ki a kérésből a törlendő entitások azonosítóit.
        // A $request->input('entities') egy tömb, amelyben az egyes elemek az
        // entitás adatait tartalmazzák, például a név, a cég, a születési dátum,
        // stb. A `pluck('id')` függvénnyel kivonjuk az azonosítókat ebből a
        // tömbből, és a végeredményt egy új tömbben tároljuk.
        // A `collect()` függvénnyel egyúttal egy új Laravel Collection objektumot
        // hozunk létre, amely alkalmasabb a további műveletek elvégzésére.
        $ids = collect($request->input('entities'))->pluck('id')->all();

        // Ellenőrizzük, hogy a kérésből kigyűjtött azonosítók tömbje nem üres-e
        if (empty($ids)) {
            // Ha a tömb üres, akkor a kérés nem tartalmazott érvényes azonosítókat,
            // ezért visszaadjuk egy 400-as hibaüzenetet.
            return response()->json([
                'success' => false, 
                'message' => 'No valid IDs provided'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Töröljük a megadott azonosítóval rendelkező entitásokat az adatbázisból.
        // A `whereIn` függvénnyel egy feltételes törlést végezhetünk el, amellyel
        // csak azokat a rekordokat töröljük, amelyek azonosítója megegyezik a
        // `$ids` tömbben szereplő azonosítókkal.
        // A `delete` függvénnyel megkapjuk a sikeresen törölt rekordok számát.
        $deleted = Entity::whereIn('id', $ids)->delete();

        /**
         * Ellenőrizzük, hogy sikerült-e bármilyen rekordot törölni
         *
         * A `$success` változóba beállítjuk azt, hogy sikerült-e legalább egy
         * rekordot törölni. Ha nem, akkor a kérés hibás, mert nem található az
         * adatbázisban a kérésben megadott azonosítóval rendelkező entitás.
         */
        $success = $deleted > 0;

        return response()->json([
            'success' => $success, 
            'deleted_count' => $deleted
        ], Response::HTTP_OK);
    }
}
