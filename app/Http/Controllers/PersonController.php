<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPersonRequest;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Repositories\PersonRepository;
use App\Services\CacheService;
use App\Services\Person\PersonService;
use App\Traits\Functions;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;

class PersonController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected PersonService $personService;
    protected string $tag = 'persons';

    public function __construct(PersonRepository $repository)
    {
        $this->personRepository = $repository;

        $this->tag = Person::getTag();
        
        $this->middleware("can:{$this->tag} list", ['only' => ['index', 'applySearch', 'getPersons', 'getPerson', 'getPersonByName']]);
        $this->middleware("can:{$this->tag} create", ['only' => ['createPerson']]);
        $this->middleware("can:{$this->tag} edit", ['only' => ['updatePerson']]);
        $this->middleware("can:{$this->tag} delete", ['only' => ['deletePerson', 'deletePersons']]);
        $this->middleware("can:{$this->tag} restore", ['only' => ['restorePerson']]);
    }

    public function index(Request $request): InertiaResponse
    {
        $roles = $this->getUserRoles($this->tag);
        
        return Inertia::render('Person/Index', [
            'can' => $roles,
        ]);
    }

    public function applySearch(Builder $query, string $search): Builder
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getPersons(Request $request): JsonResponse
    {
        try {
            $persons = $this->personService->getPersons($request);

            return response()->json($persons, Response::HTTP_OK);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getPersons query exception', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getPersons general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPerson(Request $request): JsonResponse
    {
        try {
            $person = $this->personService->getPerson($request->id);

            return response()->json($person, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'getPerson model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getPerson query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getPerson general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPersonByName(string $name): JsonResponse
    {
        try {
            $person = $this->personService->getPersonByName($name);

            return response()->json($person, Response::HTTP_OK);
        } catch ( ModelNotFoundException $ex ) {
            return $this->handleException($ex, 'getPersonByName model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'getPersonByName query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'getPersonByName general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createPerson(StorePersonRequest $request): JsonResponse
    {
        try {
            $person = $this->personService->createPerson($request);

            return response()->json($person, Response::HTTP_CREATED);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'createPerson query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'createPerson general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePerson(UpdatePersonRequest $request, int $id): JsonResponse
    {
        try{
            $person = $this->personService->updatePerson($request, $id);

            return response()->json($person, Response::HTTP_CREATED);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'updatePerson model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'updatePerson query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'updatePerson general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletePersons(Request $request): JsonResponse
        {
            try{
                $deletedCount = $this->personService->deletePersons($request);

                return response()->json($deletedCount, Response::HTTP_OK);
            } catch(ValidationException $ex) {
                return $this->handleException($ex, 'deletePersons validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
            } catch(QueryException $ex) {
                return $this->handleException($ex, 'deletePersons database error', Response::HTTP_UNPROCESSABLE_ENTITY);
            } catch(Exception $ex) {
                return $this->handleException($ex, 'deletePersons general error', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

    public function deletePerson(GetPersonRequest $request): JsonResponse
    {
        try{
            $person = $this->personService->deletePerson($request);

            return response()->json($person, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'deletePerson model not found error', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'deletePerson database error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'deletePerson general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restorePerson(GetPersonRequest $request): JsonResponse
    {
        try {
            $person = $this->personService->restorePerson($request);

            return response()->json($person, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'restorePerson model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'restorePerson query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'restorePerson general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function realDeletePerson(int $id): JsonResponse
    {
        try {
            $deleteCount = $this->personService->realDeletePerson($id);

            return response()->json($deleteCount, Response::HTTP_OK);
        } catch(ModelNotFoundException $ex) {
            return $this->handleException($ex, 'realDeletePerson model not found exception', Response::HTTP_NOT_FOUND);
        } catch(QueryException $ex) {
            return $this->handleException($ex, 'realDeletePerson query error', Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(Exception $ex) {
            return $this->handleException($ex, 'realDeletePerson general error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
