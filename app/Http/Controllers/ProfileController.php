<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Profile\StoreRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Get a list of active profiles.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Get pagination settings from config
        $defaultPerPage = config('profile.pagination.default_per_page');
        $maxPerPage = config('profile.pagination.max_per_page');

        // Get pagination parameters from the request or use defaults
        $perPage = $request->input('per_page', $defaultPerPage);
        $page = $request->input('page', 1);

        // Validate pagination parameters
        $perPage = max(1, min($maxPerPage, $perPage)); // Ensure per_page is between 1 and max_per_page
        $page = max(1, $page); // Ensure page is at least 1

        // Fetch paginated profiles
        $profiles = Profile::active()->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => ProfileResource::collection($profiles),
            'message' => __('profile.list_success'),
            'meta' => [
                'total' => $profiles->total(),
                'per_page' => $profiles->perPage(),
                'current_page' => $profiles->currentPage(),
                'last_page' => $profiles->lastPage(),
            ],
        ]);
    }

    /**
     * Store a new profile.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Upload the image
            $path = $request->file('image')->store('profiles', 'public');

            // Create the profile
            $data = $request->validated();
            $data = array_merge($data, [
                'image' => $path,
                'statut' => Profile::STATUS_PENDING,
            ]);

            $profile = Profile::create($data);

            DB::commit();

            // Return the response
            return response()->json([
                'data' => new ProfileResource($profile),
                'message' => __('profile.store_success'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // Delete the uploaded file if an error occurs
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            // Log the error            
            Log::alert('Profile creation failed: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'message' => __('profile.store_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
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
        DB::beginTransaction();

        try {
            // Update profile fields using validated data
            $profile->fill($request->validated());

            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($profile->image); // Delete old image
                $path = $request->file('image')->store('profiles', 'public'); // Store new image
                $profile->image = $path;
            }

            $profile->save();

            DB::commit();

            // Return the updated profile
            return response()->json([
                'data' => new ProfileResource($profile),
                'message' => __('profile.update_success'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error            ;
            Log::error(__('profile.update_failed') . $e->getMessage());


            // Return an error response
            return response()->json([
                'message' => __('profile.update_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a profile.
     *
     * @param Profile $profile
     * @return JsonResponse
     */
    public function destroy(Profile $profile): JsonResponse
    {
        DB::beginTransaction();

        try {
            if ($profile->image && Storage::disk('public')->exists($profile->image)) {
                Storage::disk('public')->delete($profile->image);
            }

            $profile->delete();

            DB::commit();

            return response()->json(null, 204); // Return 204 No Content
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error(__('profile.delete_failed') . $e->getMessage());

            return response()->json([
                'message' => __('profile.delete_failed'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
