<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\User;
use App\Models\UserDivisiPosition;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::where('name', '!=', 'Admin')->get();
        $positions = Position::where('name', '!=', 'Admin')->get();
        return view('auth.register', compact('roles', 'positions'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

     public function store(Request $request)
     {
         $request->validate([
             'role' => ['required', 'string', 'exists:roles,name'],
             'positions' => ['required', 'array', 'min:1'],
             'positions.*' => ['string', 'exists:positions,name'],
             'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
             'password' => ['required', 'confirmed', Rules\Password::defaults()],
         ]);
         $user = User::create([
             'email' => $request->email,
             'status_account' => 'unactive',
             'email_verified_at' => null,
             'password' => Hash::make($request->password),
         ]);
         $user->assignRole($request->role);
         foreach ($request->positions as $positionName) {
             $position = Position::where('name', $positionName)->first();
             if ($position) {
                 UserDivisiPosition::create([
                     'user_id' => $user->id,
                     'position_id' => $position->id,
                 ]);
             }
         }
         event(new Registered($user));
         Auth::login($user);
         return redirect()->route('verify-email');
     }

}
