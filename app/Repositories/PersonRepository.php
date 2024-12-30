<?php

namespace App\Repositories;

use App\Http\Requests\StorePersonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Interfaces\PersonRepositoryInterface;
use App\Models\Person;
use App\Services\CacheService;
use App\Traits\Functions;
use Override;
use Exception;

/**
 * Class PersonRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PersonRepository extends BaseRepository implements PersonRepositoryInterface
{
    use Functions;

    protected CacheService $cacheService;

    protected string $tag = 'persons';

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getActivePersons()
    {
        $model = $this->model();
        $companies = $model::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->where('active','=',1)
            ->get()->toArray();

        return $companies;
    }

    public function getPersons(Request $request)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, json_encode($request->all()));

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($request) {
                $personQuery = Person::search($request);
                return $personQuery->get();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getPersons error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function getPerson(int $id)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, (string) $id);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($id) {
                return Person::findOrFail($id);
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getPerson error', ['id' => $id]);
            throw $ex;
        }
    }

    public function getPersonByName(string $name)
    {
        try {
            $cacheKey = $this->generateCacheKey($this->tag, $name);

            return $this->cacheService->remember($this->tag, $cacheKey, function () use ($name) {
                return Person::where('name', '=', $name)->firstOrFail();
            });
        } catch(Exception $ex) {
            $this->logError($ex, 'getPersonByName error', ['name' => $name]);
            throw $ex;
        }
    }

    public function createPerson(StorePersonRequest $request)
    {
        try{
            $person = Person::create($request->all());

            $this->cacheService->forgetAll($this->tag);

            return $person;
        } catch(Exception $ex) {
            $this->logError($ex, 'createPerson error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function updatePerson(Request $request, int $id)
    {
        try {
            $person = null;

            DB::transaction(function() use($request, $id, &$person) {
                $person = Person::findOrFail($id)->lockForUpdate();

                $person->update($request->all());
                $person->refresh();

                $this->cacheService->forgetAll($this->tag);
            });

            return $person;
        } catch(Exception $ex) {
            $this->logError($ex, 'updatePerson error', ['id' => $id, 'request' => $request->all()]);
            throw $ex;
        }
    }

    public function deletePersons(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1', // Kötelező, legalább 1 id kell
                'ids.*' => 'integer|exists:persons,id', // Az id-k egész számok és létező cégek legyenek
            ]);

            $ids = $validated['ids'];
            $deletedCount = Person::whereIn('id', $ids)->delete();
            $this->cacheService->forgetAll($this->tag);

            return $deletedCount;
        } catch(Exception $ex) {
            $this->logError($ex, 'deletePersons error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function deletePerson(Request $request)
    {
        try {
            $person = Person::findOrFail($request->id);
            $person->delete();

            $this->cacheService->forgetAll($this->tag);

            return $person;
        } catch(Exception $ex) {
            $this->logError($ex, 'deletePerson error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    public function restorePerson(Request $request)
    {
        try {
            $person = Person::withTrashed()->findOrFail($request->id);
            $person->restore();

            $this->cacheService->forgetAll($this->tag);

            return $person;
        } catch(Exception $ex) {
            $this->logError($ex, 'restorePerson error', ['request' => $request->all()]);
            throw $ex;
        }
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    #[Override]
    public function model()
    {
        return Person::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    #[Override]
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
