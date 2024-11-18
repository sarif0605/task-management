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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $deal = DealProject::with('prospect')->find($id);
        if (!$deal) {
            return response()->json(['error' => 'Survey not found'], 404);
        }
        return response()->json([
            'deal' => $deal
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $deal = DealProject::find($id);
        if (!$deal) {
            return response()->json([
                'error' => 'Survey not found'
            ], 404);
        }
        return response()->json([
            'deal' => $deal
        ]);
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
                // Delete old file if exists
                if ($deal->rab) {
                    Storage::delete('public/rab/' . $deal->rab);
                }

                $rabFile = $request->file('rab');
                $rabName = uniqid() . '_' . $rabFile->getClientOriginalName();
                $rabFile->storeAs('public/rab', $rabName);
                $data['rab'] = $rabName;
            }

            // Handle RAP file
            if ($request->hasFile('rap')) {
                // Delete old file if exists
                if ($deal->rap) {
                    Storage::delete('public/rap/' . $deal->rap);
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

            return response()->json([
                'success' => true,
                'message' => 'Deal Project berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Deal Project Update Failed:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui Deal Project: ' . $e->getMessage()
            ], 500);
        }
    }
    // public function update(DealProjectUpdateRequest $request, string $id)
    // {
    //     $deal = DealProject::find($id);
    //     if (!$deal) {
    //         return redirect()->route('deal_project.edit', $id)
    //             ->with('error', 'Deal Project dengan ID ' . $id . ' tidak ditemukan.');
    //     }
    //     DB::beginTransaction();
    //     try {
    //         $data = $request->validated();
    //         if ($request->hasFile('rab')) {
    //             if ($deal->rab) {
    //                 $oldPdfPath = "public/rab/{$deal->rab}"; // Adjusted path
    //                 if (Storage::exists($oldPdfPath)) {
    //                     Storage::delete($oldPdfPath);
    //                 }
    //             }
    //             $pdfFile = $request->file('rab');
    //             $pdfName = uniqid() . '_' . $pdfFile->getClientOriginalName();
    //             $pdfFile->storeAs('public/rab', $pdfName);
    //             $data['rab'] = $pdfName;
    //         }

    //         // Handle Excel file update
    //         if ($request->hasFile('rap')) {
    //             if ($deal->rap) {
    //                 $oldExcelPath = "public/rap/{$deal->rap}"; // Adjusted path
    //                 if (Storage::exists($oldExcelPath)) {
    //                     Storage::delete($oldExcelPath);
    //                 }
    //             }

    //             $excelFile = $request->file('rap');
    //             $excelName = uniqid() . '_' . $excelFile->getClientOriginalName();
    //             $excelFile->storeAs('public/rap', $excelName);
    //             $data['rap'] = $excelName;
    //         }
    //         $deal->update($data);
    //         DB::commit();
    //         return redirect()->route('deal_projects')
    //             ->with('success', 'Penawaran Project berhasil diperbarui.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Penawaran Project Update Failed:', [
    //             'message' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return redirect()->route('deal_projects.edit', $id)
    //             ->with('error', 'Gagal memperbarui Penawaran Project: ' . $e->getMessage());
    //     }
    // }

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
