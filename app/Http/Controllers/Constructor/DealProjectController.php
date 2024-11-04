<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\DealProject\DealProjectCreateRequest;
use App\Http\Requests\DealProject\DealProjectUpdateRequest;
use App\Models\DealProject;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DealProjectUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct()
    {
        $this->middleware(['isVerificationAccount', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

     public function index(Request $request)
     {
         if ($request->ajax()) {
             $user = Auth::user();
             $query = DealProject::with('prospect');
             if ($user->position === 'pengawas') {
                 $query->whereHas('deal_project_users', function($q) use ($user) {
                     $q->where('user_id', $user->id);
                 });
             }
             $dealProjects = $query->orderBy('created_at', 'DESC')->get();
             return response()->json(['data' => $dealProjects]);
         }

         return view('contractor.done_deal.index');
     }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prospect = Prospect::all();
        $users = User::where('position', 'pengawas')->get();
        return view('contractor.done_deal.create', compact('prospect', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DealProjectCreateRequest $request)
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($validatedData) {
            $dealProject = DealProject::create($validatedData);
            foreach ($validatedData['users'] as $userId) {
                DealProjectUsers::create([
                    'deal_project_id' => $dealProject->id,
                    'user_id' => $userId
                ]);
            }
            if (isset($validatedData['prospect_id'])) {
                $prospect = Prospect::find($validatedData['prospect_id']);
                if ($prospect) {
                    $prospect->status = 'deal';
                    $validatedData['lokasi'] = $prospect->lokasi;
                    $prospect->save();
                } else {
                    return response()->json(['error' => 'Prospect not found.'], 404);
                }
            }
        });
        return response()->json(['message' => 'Data berhasil disimpan'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dealProject = DealProject::with('prospect')->find($id);
        if (!$dealProject) {
            return redirect()->route('done_deals')
            ->with('error', 'Deal Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.done_deal.show', compact('dealProject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dealProject = DealProject::find($id);
        $prospect = Prospect::all();
        if (!$dealProject) {
            return redirect()->route('advertising.sales.deal_project.index')
            ->with('error', 'Deal Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('deal_projects.edit', compact('dealProject', 'prospect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DealProjectUpdateRequest $request, string $id)
    {
        $dealProject = DealProject::find($id);
        if (!$dealProject) {
            return redirect()->route('advertising.sales.deal_project.index')
            ->with('error', 'Deal Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $dealProject->update($data);
        return redirect()->route('advertising.sales.deal_project.index')->with('success', 'Deal Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dealProject = DealProject::find($id);
        if (!$dealProject) {
            return redirect()->route('advertising.sales.deal_project.index')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        $dealProject->delete();
        return redirect()->route('advertising.sales.deal_project.index')->with('success', 'Deal Project deleted successfully.');
    }
}
