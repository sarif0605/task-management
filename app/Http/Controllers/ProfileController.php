<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profiles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{

    /**
     * Display the user's profile form.
     */

     public function update(ProfileUpdateRequest $request)
    {
        try {
            $data = $request->validated();
            $currentUser = auth()->user();
            $profileData = [
                'name' => $data['name'],
                'nik' => $data['nik'],
                'birth_date' => $data['birth_date'],
                'address' => $data['address'],
                'phone' => $data['phone'],
            ];
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('public/profile', $imageName);
                if ($currentUser->profile && $currentUser->profile->image_url) {
                    $oldImagePath = "public/profile/{$currentUser->profile->image_url}";
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }
                $profileData['image_url'] = $imageName;
            } else {
                if ($currentUser->profile) {
                    $profileData['image_url'] = $currentUser->profile->image_url;
                }
            }
            Profiles::updateOrCreate(
                ['user_id' => $currentUser->id],
                $profileData
            );
            if (isset($data['email'])) {
                $currentUser->email = $data['email'];
                $currentUser->save();
            }
            return Redirect::route('profile.edit')->with('status', 'profile berhasil di perbarui');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return Redirect::route('profile.edit')
                ->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()]);
        }
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
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
