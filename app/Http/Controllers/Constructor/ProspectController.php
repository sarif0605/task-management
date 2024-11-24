<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Prospect\ProspectCreateRequest;
use App\Http\Requests\Prospect\ProspectUpdateRequest;
use App\Models\Prospect;
use App\Exports\ProspectExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FilePenawaranProjects;
use App\Models\PenawaranProject;
use App\Models\SurveyImages;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProspectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $prospects = Prospect::withCount('survey')
                ->withCount('penawaran_project')
                ->withCount('deal_project')
                ->orderBy('created_at', 'DESC')
                ->get();

            return response()->json(['data' => $prospects]);
        }

        // Mengambil data users dengan relasi profile dan position, kecuali Admin
        $users = User::with(['profile', 'position'])
            ->whereDoesntHave('position', function ($query) {
                $query->where('name', 'Admin');
            })
            ->get();

        // Return view dengan data users
        return view('contractor.prospect.index', compact('users'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new ProspectExport, $request->file('file'));
        return redirect('/prospects')->with('status','Berhasil melakukan import data prospect!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contractor.prospect.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProspectCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $prospect = new Prospect($data);
            $prospect->save();
            return redirect()->route('prospects')->with('status', 'Data prospect berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data prospect gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prospect = Prospect::find($id);
        if (!$prospect) {
            return redirect()->route('prospects')
            ->with('error', 'Prospect dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.prospect.show', compact('prospect'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prospect = Prospect::find($id);
        if (!$prospect) {
            return redirect()->route('prospects.edit')
            ->with('error', 'Prospect dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.prospect.edit', compact('prospect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProspectUpdateRequest $request, string $id)
    {
        $prospect = Prospect::find($id);
        if (!$prospect) {
            return redirect()->route('prospects.edit')
            ->with('error', 'Prospect dengan Nama ' . $prospect->nama_produk . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $prospect->update($data);
        return redirect()->route('prospects')->with('status', 'Data prospect berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $prospect = Prospect::find($id);

        if (!$prospect) {
            return response()->json([
                'success' => false,
                'message' => 'Prospect gagal dihapus. Data tidak ditemukan.',
            ], 404); // Menggunakan kode status HTTP 404 untuk resource not found
        }
        foreach ($prospect->survey as $survey) {
            $existingImages = SurveyImages::where('survey_id', $survey->id)->get();
            foreach ($existingImages as $existingImage) {
                Storage::disk('local')->delete('public/survey/' . $existingImage->image_url);
                $existingImage->delete();
            }
            $survey->delete();
        }

        foreach ($prospect->penawaran_project as $penawaran) {
            $existingImages = FilePenawaranProjects::where('penawaran_project_id', $penawaran->id)->get();
            foreach ($existingImages as $existingImage) {
                Storage::disk('local')->delete('public/penawaran/' .$existingImage->file);
                $existingImage->delete();
            }
            $penawaran->delete();
        }

        foreach ($prospect->deal_project as $deal) {
            if (!empty($deal->rab)) {
                Storage::disk('local')->delete('public/rab/' . $deal->rab);
            }
            if (!empty($deal->rap)) {
                Storage::disk('local')->delete('public/rap/' . $deal->rap);
            }
            $deal->delete();
        }
        $prospect->delete();
        return response()->json([
            'success' => true,
            'message' => 'Prospect berhasil dihapus.',
        ], 200);
    }
}
