<?php

namespace App\Http\Controllers;

use App\Http\Requests\DirectorRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Director;
use App\Http\Resources\DirectorResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DirectorController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Director::class);

        $directors = Director::latest()->paginate(10);
        return DirectorResource::collection($directors);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): DirectorResource
    {
        $director = Director::findOrFailCustom($id);

        $this->authorize('view', $director);
        return new DirectorResource($director);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DirectorRequest $request)
    {
        $this->authorize('create', Director::class);

        $director = Director::fill($request->validated());

        if ($request->hasFile('image')) {
            $director->image = $request->file('image')->store('directors_images');
        }

        return new DirectorResource($director);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
