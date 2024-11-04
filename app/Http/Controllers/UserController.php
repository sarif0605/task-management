<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['isVerificationAccount', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy', 'index', 'show');
    }

     public function index(Request $request)
     {
         if ($request->ajax()) {
             $user = Auth::user();
             $query = User::where('position', '!=', 'admin')->orderBy('created_at', 'DESC')->get();
             return response()->json(['data' => $query]);
         }

         return view('contractor.done_deal.index');
     }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('/users')
            ->with('error', 'User dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('user.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')
            ->with('error', 'User dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('user.edit', compact('dealProject', 'prospect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')
            ->with('error', 'User dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $user->update($data);
        return redirect()->route('user.index')->with('success', 'Deal Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')
            ->with('error', 'User dengan ID ' . $id . ' tidak ditemukan.');
        }
        $user->delete();
        return redirect()->route('advertising.sales.deal_project.index')->with('success', 'Deal Project deleted successfully.');
    }
}
