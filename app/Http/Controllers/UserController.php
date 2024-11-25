<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy', 'index', 'show');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('profile', 'position')
                ->whereHas('position', function($query) {
                    $query->where('name', '!=', 'admin');
                })
                ->orderBy('created_at', 'DESC')
                ->get()
                ->map(function($user) {
                    $user->positions = $user->position->pluck('name')->join(', ');
                    return $user;
                });
            return response()->json(['data' => $query]);
        }
        return view('contractor.user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users')->with('error', 'User dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users')
            ->with('error', 'User dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users')
            ->with('error', 'User dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $user->update($data);
        return redirect()->route('users')->with('success', 'Deal Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users')
            ->with('error', 'User dengan ID ' . $id . ' tidak ditemukan.');
        }
        $user->delete();
        return redirect()->route('users')->with('success', 'Deal Project deleted successfully.');
    }
}
