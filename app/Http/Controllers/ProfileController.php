<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profiles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function token()
    {
        $client_id = \Config('services.google.client_id');
        $client_secret = \Config('services.google.client_secret');
        $refresh_token = \Config('services.google.refresh_token');
        $folder_id = \Config('services.google.folder_id');
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
        ]);
        $accessToken = json_decode((string) $response->getBody(), true)['access_token'];
        return $accessToken;
    }

    /**
     * Display the user's profile form.
     */

    public function update(ProfileUpdateRequest $request)
    {
        $data = $request->validated();
        $currentUser = auth()->user();
        $accessToken = $this->token();
        dd($accessToken);
        $name = $request->image_url->getClientOriginalName();
        $path=$request->image_url->getRealPath();
        $profileData = Profiles::updateOrCreate(
            ['user_id' => $currentUser->id],
            [
                'name' => $data['name'],
                'nik' => $data['nik'],
                'birth_date' => $data['birth_date'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'user_id' => $currentUser->id,
                'image_url' => $request->hasFile('image') ? null : $currentUser->profile->image_url,
            ]
        );
        $response=Http::withToken($accessToken)
        ->attach('data',file_get_contents($path),$name)
        ->post('https://www.googleapis.com/upload/drive/v3/files',
            ['name'=>$name],
            ['Content-Type'=>'application/octet-stream',]);
            if ($response->successful()) {
                $file_id = json_decode($response->body())->id;
                $profileData->image_url = $request->image;
                $profileData->image_public_id=$name;
                $profileData->image_public_id = $file_id;
            }
        // if ($request->hasFile('image')) {
        //     if ($profileData->image_public_id) {
        //         Cloudinary::destroy($profileData->image_public_id);
        //     }
        //     $cloudinaryImage = $request->file('image')->storeOnCloudinary('bnp');
        //     $url = $cloudinaryImage->getSecurePath();
        //     $public_id = $cloudinaryImage->getPublicId();
        //     $profileData->image_url = $url;
        //     $profileData->image_public_id = $public_id;
        // }
        $profileData->save();
        if (isset($data['email'])) {
            $currentUser->email = $data['email'];
            $currentUser->save();
        }
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
