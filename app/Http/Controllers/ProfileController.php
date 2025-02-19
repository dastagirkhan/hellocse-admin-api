<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function __construct() {}

    public function index()
    {
        return Profile::where('statut', 'actif')->get(['id', 'nom', 'prenom', 'image']);
    }

    public function publicIndex()
    {
        $profiles = Profile::where('statut', 'actif')->get();
        return view('profiles.index', compact('profiles'));
    }


    public function store(ProfileRequest $request)
    {
        $path = $request->file('image')->store('profiles', 'public');

        $profile = Profile::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'image' => $path,
            'statut' => 'en attente', // Default status
        ]);

        return response()->json($profile, 201);
    }


    public function update(ProfileRequest $request, Profile $profile)
    {
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($profile->image);
            $path = $request->file('image')->store('profiles', 'public');
            $profile->image = $path;
        }

        $profile->nom = $request->nom;
        $profile->prenom = $request->prenom;
        $profile->save();

        return response()->json($profile);
    }

    public function destroy(Profile $profile)
    {
        Storage::disk('public')->delete($profile->image);
        $profile->delete();

        return response()->json(null, 204);
    }
}
