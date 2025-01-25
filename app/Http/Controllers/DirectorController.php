<?php

namespace App\Http\Controllers;

use App\Http\Requests\DirectorRequest;
use App\Services\ImageService;
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
    public function store(DirectorRequest $request, ImageService $imageService): DirectorResource
    {
        $this->authorize('create', Director::class);

        $director = Director::create($request->validated());

        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->getClientOriginalName();
            $director->image = $imageService->storeImage($request->file('image'),$imageName, $director->id, 'directors_image');
        }

        return new DirectorResource($director);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DirectorRequest $request, int $id, ImageService $imageService): DirectorResource
    {
        $director = Director::findOrFailCustom($id);

        $this->authorize('update', $director);

        $director->update($request->validated());

        if ($request->hasFile('image')) {

            if ($director->image) {
                $imageService->deleteImage($director->image);
            }

            $imageName = $request->file('image')->getClientOriginalName();
            $director->image = $imageService->storeImage($request->file('image'),$imageName, $director->id, 'directors_image');
        }

        return new DirectorResource($director);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, ImageService $imageService): void
    {
        $director = Director::findOrFailCustom($id);

        $this->authorize('delete', $director);

        if ($director->image) {
            $imageService->deleteImage($director->image);
        }

        $director->delete();
    }
}
