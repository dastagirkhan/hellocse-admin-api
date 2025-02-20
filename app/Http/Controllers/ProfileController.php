<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    /**
     * ProfileController constructor.
     */
    public function __construct() {}

    /**
     * Get a list of active profiles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve profiles with 'actif' status and return specific fields
        return Profile::where('statut', 'actif')->get(['id', 'nom', 'prenom', 'image']);
    }

    /**
     * Store a new profile.
     *
     * @param ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProfileRequest $request)
    {
        // Store the uploaded image in the 'profiles' directory within 'public' disk
        $path = $request->file('image')->store('profiles', 'public');

        // Create a new profile with default 'en attente' status
        $profile = Profile::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'image' => $path,
            'statut' => 'en attente', // Default status
        ]);

        return response()->json($profile, 201);
    }

    /**
     * Update an existing profile.
     *
     * @param ProfileRequest $request
     * @param Profile $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileRequest $request, Profile $profile)
    {
        // Handle image update if a new file is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image from storage
            Storage::disk('public')->delete($profile->image);
            // Store the new image
            $path = $request->file('image')->store('profiles', 'public');
            $profile->image = $path;
        }

        // Update other profile fields
        $profile->nom = $request->nom;
        $profile->prenom = $request->prenom;

        // Update status if provided
        if ($request->statut) {
            $profile->statut = $request->statut;
        }

        // Save changes
        $profile->save();

        return response()->json($profile);
    }

    /**
     * Delete a profile.
     *
     * @param Profile $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Profile $profile)
    {
        // Delete the profile image from storage
        Storage::disk('public')->delete($profile->image);

        // Delete the profile record
        $profile->delete();

        return response()->json(null, 204);
    }
}
