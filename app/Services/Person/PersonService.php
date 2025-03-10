<?php

namespace App\Services\Person;

use App\Models\Person;
use App\Repositories\PersonRepository;
use Exception;
use Illuminate\Http\Request;

class PersonService
{
    protected PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository){
        $this->personRepository = $personRepository;
    }
    
    public function getActivePersons(): array
    {
        try {
            return $this->personRepository->getActivePersons();
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getPersons(Request $request)
    {
        try {
            return $this->personRepository->getPersons($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getPerson(int $id)
    {
        try {
            return $this->personRepository->getPerson($id);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function getPersonByName(string $name)
    {
        try {
            return $this->personRepository->getPersonByName($name);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function createPerson(Request $request)
    {
        try {
            return $this->personRepository->createPerson($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function updatePerson(Request $request, int $id): ?Person
    {
        try {
            return $this->personRepository->updatePerson($request, $id);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function deletePersons(Request $request): bool
    {
        try{
            return $this->personRepository->deletePersons($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function deletePerson(Request $request)
    {
        try {
            return $this->personRepository->deletePerson($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function restorePerson(Request $request)
    {
        try {
            return $this->personRepository->restorePerson($request);
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function realDeletePerson(int $id): ?Person
    {
        try {
            return $this->personRepository->realDeletePerson($id);
        } catch(Exception $ex) {
            throw $ex;
        }
    }
}