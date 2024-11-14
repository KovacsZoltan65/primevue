<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends Controller
{
    public function __construct() {
        //
    }

    /**
     * Jelenítse meg az entitások listáját.
     *
     * Lekéri a név szerint rendezett cégek listáját, és átadja azt,
     * a keresési paraméterrel együtt a kérelemből az Inertia nézetbe.
     *
     * @param Request $request A keresési paramétereket tartalmazó HTTP-kérelem példány.
     * @return \Inertia\Response Az Inertia válasz a cégek adataival.
     */
    public function index(Request $request)
    {
        $companies = \App\Models\Company::select('id', 'name')
            ->orderBy('name')->get()->toArray();

        return Inertia::render('Entity/Index', [
            'search' => $request->get('search'),
            'companies' => $companies
        ]);
    }

    /**
     * Módosítsa a lekérdezést a keresési paraméter alapján.
     *
     * Ha a keresési paraméter nem üres, akkor a lekérdezés tartalmazza
     * a feltételt, hogy a vállalat neve tartalmazza a keresési paramétert.
     *
     * @param Builder $query A lekérdezés, amelyet módosítani kell.
     * @param string $search A keresési paraméter.
     * @return Builder A módosított lekérdezés.
     */
    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            // Adjon hozzá egy where záradékot a lekérdezéshez azzal a feltétellel, hogy a
            // az ország nevének tartalmaznia kell a keresési lekérdezést
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Szerezd meg az entitások listáját a keresési paraméter alapján.
     *
     * Ez a módszer a keresési lekérdezést az adatbázis-lekérdezésre alkalmazza.
     * Ha a keresési lekérdezés nem üres, akkor egy where záradékot ad a lekérdezéshez
     * azzal a feltétellel, hogy az entitás nevének tartalmaznia kell a keresési lekérdezést.
     *
     * @param  \Illuminate\Http\Request  $request  A HTTP kérés objektum, amely tartalmazza a keresési paramétereket.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection Az entitások listáját tartalmazó JSON-válasz.
     */
    public function getEntities(Request $request): AnonymousResourceCollection
    {
        $entityQuery = Entity::Search($request);
        $entities = EntityResource::collection($entityQuery->get());

        return $entities;
    }

    /**
     * Visszaadja a megadott azonosítóval rendelkező entitást.
     *
     * @param int $id Az entitás azonosítója.
     * @return \Illuminate\Http\JsonResponse A keresett entitás adatait tartalmazó JSON-válasz.
     */
    public function getEntity(int $id): JsonResponse
    {
        $entity = Entity::find($id);

        return response()->json($entity, Response::HTTP_OK);
    }

    /**
     * Visszaadja a megadott névvel rendelkező entitást.
     *
     * @param string $name Az entitás neve.
     * @return \Illuminate\Http\JsonResponse A keresett entitás adatait tartalmazó JSON-válasz.
     */
    public function getEntityByName(string $name): JsonResponse
    {
        $entity = Entity::where('name', $name)->first();

        return response()->json($entity, Response::HTTP_OK);
    }

    /**
     * Hozzon létre egy új entitást.
     *
     * @param StoreEntityRequest $request A HTTP kérés objektum, amely tartalmazza az új entitás adatait.
     * @return \Illuminate\Http\JsonResponse Az újonnan létrehozott entitás adatait tartalmazó JSON-válasz.
     */
    public function createEntity(StoreEntityRequest $request): JsonResponse
    {
        $entity = Entity::create($request->all());

        return response()->json($entity, Response::HTTP_OK);
    }

    /**
     * Módosít egy entitást az adatbázisban.
     *
     * A módosított entitás sikerességét jelző JSON-válasz kerül visszaadásra.
     *
     * @param  \App\Http\Requests\UpdateEntityRequest  $request  A HTTP kérés objektum, amely tartalmazza a módosítani kívánt entitás adatait.
     * @param  int  $id  A módosítani kívánt entitás azonosítója.
     * @return \Illuminate\Http\JsonResponse A sikerességet jelző JSON-válasz.
     */
    public function updateEntity(UpdateEntityRequest $request, int $id): JsonResponse
    {
        $old_entity = Entity::find($id);

        $success = $old_entity->update($request->all());

        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    /**
     * Töröljön egy entitást az adatbázisból.
     *
     * A törölt entitás sikerességét jelző JSON-válasz kerül visszaadásra.
     *
     * @param int $id A törölni kívánt entitás azonosítója.
     * @return \Illuminate\Http\JsonResponse A sikerességet jelző JSON-válasz.
     */
    public function deleteEntity(int $id): JsonResponse
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
    public function deleteEntities(Request $request): JsonResponse
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
