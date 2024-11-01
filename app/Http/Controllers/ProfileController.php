<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profiles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */


    // public function index() : JsonResponse
    // {
    //     $currentUser = auth()->user();
    //     $profile = Profile::with('user')->where('user_id', $currentUser->id)->first();
    //     return response()->json([
    //        'message' => 'Tampil Data',
    //         'data' => $profile
    //     ], 201);
    // }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $currentUser = auth()->user();
        $profileData = Profiles::updateOrCreate(
            ['user_id' => $currentUser->id],
            [
                'age' => $data['age'],
                'address' => $data['address'],
                'biodata' => $data['biodata'],
                'user_id' => $currentUser->id,
            ]
        );
        $profileData->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
