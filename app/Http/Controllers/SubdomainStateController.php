<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubdomainStateRequest;
use App\Http\Requests\UpdateSubdomainStateRequest;
use App\Http\Resources\SubdomainStateResource;
use App\Models\SubdomainState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class SubdomainStateController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        return Inertia::render('', [
            'search' => $request('search'),
        ]);
    }

    public function applySearch(Builder $query, string $search)
    {
        return $query->when($search, function($query, string $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function getSubdomainStates(Request $request): AnonymousResourceCollection
    {
        $subdomainStateQuery = SubdomainState::search($request);

        $subdomainStates = SubdomainStateResource::collection($subdomainStateQuery);

        return $subdomainStates;
    }

    public function createSubdomainState(StoreSubdomainStateRequest $request)
    {
        $subdomainState = SubdomainState::create($request->all());

        return response()->json($subdomainState, Response::HTTP_OK);
    }

    public function updateSubdomainState(UpdateSubdomainStateRequest $request, int $id)
    {
        $success = SubdomoanState::find($id)->update($request->all());

        return response()->json(['success' => $success], Response::HTTP_OK);
    }

    public function deleteSubdomainState(int $id)
    {
        $success = SubdomainState::find($id)->delete();

        return response()->json(['success' => $success], Response::HTTP_OK);
    }
}
