<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Profile\StoreRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Resources\ProfileResource;

class ProfileController extends Controller
{
    /**
     * Get a list of active profiles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(ProfileResource::collection(Profile::active()->get()));
    }

    /**
     * Store a new profile.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $path = $request->file('image')->store('profiles', 'public');

        $profile = Profile::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'image' => $path,
            'statut' => 'en attente',
        ]);

        return response()->json(new ProfileResource($profile), 201);
    }

    /**
     * Update an existing profile.
     *
     * @param UpdateRequest $request
     * @param Profile $profile
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Profile $profile): JsonResponse
    {
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($profile->image);
            $path = $request->file('image')->store('profiles', 'public');
            $profile->image = $path;
        }

        $profile->nom = $request->nom;
        $profile->prenom = $request->prenom;

        if ($request->statut) {
            $profile->statut = $request->statut;
        }

        $profile->save();

        return response()->json(new ProfileResource($profile));
    }

    /**
     * Delete a profile.
     *
     * @param Profile $profile
     * @return JsonResponse
     */
    public function destroy(Profile $profile): JsonResponse
    {
        Storage::disk('public')->delete($profile->image);
        $profile->delete();

        return response()->json(null, 204);
    }
}
