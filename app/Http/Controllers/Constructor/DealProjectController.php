<?php

namespace App\Http\Controllers\Constructor;

use App\Models\DealProject;
use App\Models\Prospect;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\DealProject\DealProjectCreateRequest;
use App\Http\Requests\DealProject\DealProjectUpdateRequest;
use App\Models\DealProjectUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function store(DealProjectCreateRequest $request)
    {
        // Log incoming request data for debugging
        Log::info('DealProject store request data:', $request->all());

        try {
            $result = DB::transaction(function () use ($request) {
                $data = $request->validated();
                $dealProject = DealProject::create([
                    'prospect_id' => $data['prospect_id'],
                    'date' => $data['date'],
                    'harga_deal' => $data['harga_deal'],
                    'keterangan' => $data['keterangan'] ?? null,
                ]);
                Log::info('DealProject created:', ['id' => $dealProject->id]);
                foreach ($data['users'] as $userId) {
                    DealProjectUsers::create([
                        'deal_project_id' => $dealProject->id,
                        'user_id' => $userId,
                    ]);
                }
                Log::info('DealProject users assigned:', ['users' => $data['users']]);
                $prospect = Prospect::findOrFail($data['prospect_id']);
                $prospect->update(['status' => 'survey']);
                Log::info('Prospect status updated:', [
                    'prospect_id' => $prospect->id,
                    'new_status' => 'survey'
                ]);
                return $dealProject;
            });
            return response()->json([
                'success' => true,
                'message' => 'Deal Project berhasil dibuat',
                'data' => $result
            ], 201);

        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating deal project:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat Deal Project',
                'error' => $e->getMessage()
            ], 500);
        }
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
            return redirect()->route('done_deals')
            ->with('error', 'Deal Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.done_deal.edit', compact('dealProject', 'prospect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DealProjectUpdateRequest $request, string $id)
    {
        $dealProject = DealProject::find($id);
        if (!$dealProject) {
            return redirect()->route('done_deals')
            ->with('error', 'Deal Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $dealProject->update($data);
        return redirect()->route('done_deals')->with('success', 'Deal Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dealProject = DealProject::find($id);
        if (!$dealProject) {
            return redirect()->route('done_deals')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        $dealProject->delete();
        return redirect()->route('done_deals')->with('success', 'Deal Project deleted successfully.');
    }
}
