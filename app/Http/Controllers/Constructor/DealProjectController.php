<?php

namespace App\Http\Controllers\Constructor;

use App\Models\DealProject;
use App\Models\Prospect;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\DealProject\DealProjectCreateRequest;
use App\Http\Requests\DealProject\DealProjectUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DealProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct()
    {
        $this->middleware(['verified', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $users = User::with(['profile', 'position'])->get();
        $query = DealProject::with('prospect');
        if ($user->position === 'pengawas') {
            $query->whereHas('deal_project_users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        $dealProjects = $query->orderBy('created_at', 'DESC')->get();
        if ($request->ajax()) {
            return response()->json(['data' => $dealProjects]);
        }
        return view('contractor.done_deal.index', compact('dealProjects', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::with(['profile', 'position'])
                    ->whereDoesntHave('position', function($query) {
                        $query->where('name', 'Admin');
                    })
                    ->get();

        return view('contractor.prospect.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DealProjectCreateRequest $request){
        $data = $request->validated();
        $deal = new DealProject($data);
        $deal->prospect_id = $data['prospect_id'];
        $deal->save();
        if (isset($data['prospect_id'])) {
            $prospect = Prospect::find($data['prospect_id']);
            if ($prospect) {
                $prospect->status = 'deal';
                $prospect->save();
            }
        }
        return response()->json(['message' => 'Deal created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     */

     public function show($id) {
        $dealProject = DealProject::with(['prospect', 'deal_project_users.user.profile'])->find($id);
        if (!$dealProject) {
            return redirect()->route('deal_projects')->with('error', 'Deal Project tidak ditemukan.');
        }
        return view('contractor.done_deal.show', compact('dealProject'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $deal = DealProject::find($id);

        if (!$deal) {
            return redirect()->route('deal_projects.edit')
                ->with('error', 'Deal Projects dengan ID ' . $id . ' tidak ditemukan.');
        }

        // Ambil semua user dengan posisi "Pengawas"
        $users = User::with(['profile', 'position'])
            ->whereHas('position', function ($query) {
                $query->where('name', 'Pengawas');
            })
            ->get();
            $selectedUsers = $deal->users()->select('users.id')->pluck('users.id')->toArray();
        return view('contractor.done_deal.edit', compact('deal', 'users', 'selectedUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DealProjectUpdateRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $deal = DealProject::findOrFail($id);
            $data = $request->validated();

            // Handle RAB file
            if ($request->hasFile('rab')) {
                if (!empty($deal->rab)) {
                    Storage::disk('local')->delete('public/rab/' . $deal->rab);
                }
                $rabFile = $request->file('rab');
                $rabName = uniqid() . '_' . $rabFile->getClientOriginalName();
                $rabFile->storeAs('public/rab', $rabName);
                $data['rab'] = $rabName;
            }

            // Handle RAP file
            if ($request->hasFile('rap')) {
                if (!empty($deal->rap)) {
                    Storage::disk('local')->delete('public/rap/' . $deal->rap);
                }
                $rapFile = $request->file('rap');
                $rapName = uniqid() . '_' . $rapFile->getClientOriginalName();
                $rapFile->storeAs('public/rap', $rapName);
                $data['rap'] = $rapName;
            }

            $deal->update($data);

            // Update related users if provided
            if (isset($data['users'])) {
                $deal->deal_project_users()->delete(); // Remove old relations
                foreach ($data['users'] as $userId) {
                    $deal->deal_project_users()->create([
                        'user_id' => $userId
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('deal_projects')->with('success', 'berhasil update data');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('deal_projects')->with('error', 'gagal mengubah data');
        }
    }

    public function destroy(string $id)
    {
        $dealProject = DealProject::find($id);

        // Jika Deal Project tidak ditemukan
        if (!$dealProject) {
            return response()->json([
                'success' => false,
                'message' => 'Deal Project dengan ID ' . $id . ' tidak ditemukan.'
            ], 404);
        }

        // Menghapus file RAB jika ada
        if (!empty($dealProject->rab)) {
            Storage::disk('local')->delete('public/rab/' . $dealProject->rab);
        }

        // Menghapus file RAP jika ada
        if (!empty($dealProject->rap)) {
            Storage::disk('local')->delete('public/rap/' . $dealProject->rap);
        }

        // Menghapus Deal Project
        $dealProject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deal Project deleted successfully.'
        ], 200);
    }
}
