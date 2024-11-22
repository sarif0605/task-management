<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenawaranProject\PenawaranProjectCreateRequest;
use App\Http\Requests\PenawaranProject\PenawaranProjectUpdateRequest;
use App\Models\FilePenawaranProjects;
use App\Models\PenawaranProject;
use App\Models\Prospect;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PenawaranProjectController extends Controller
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
            $penawaran_project = PenawaranProject::with('prospect', 'file_penawaran_project')->orderBy('created_at', 'DESC')->get();
            return response()->json(['data' => $penawaran_project]);
        }
        return view('contractor.penawaran_project.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($prospect_id)
    {
        return view('contractor.penawaran_project.create', compact('prospect_id'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penawaran = PenawaranProject::with('prospect')->find($id);
        if (!$penawaran) {
            return response()->json(['error' => 'Penawaran not found'], 404);
        }
        return response()->json([
            'penawaran' => $penawaran
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penawaran = PenawaranProject::find($id);
        if (!$penawaran) {
            return redirect()->route('penawaran_projects.edit')
            ->with('error', 'Penawaran Projects dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.penawaran_project.edit', compact('penawaran'));
    }

    public function update(PenawaranProjectUpdateRequest $request, string $id)
    {
        $penawaran = PenawaranProject::find($id);
        if (!$penawaran) {
            return response()->json([
                'error' => 'Survey tidak ditemukan.'
            ], 404);
        }
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $penawaran->update($data);

            if ($penawaran->prospect_id) {
                $prospect = Prospect::find($penawaran->prospect_id);
                if ($prospect) {
                    $prospect->status = 'penawaran';
                    $prospect->save();
                }
            }
            // Handle new images
            if ($request->hasFile('file')) {
                $existingImages = FilePenawaranProjects::where('penawaran_project_id', $penawaran->id)->get();
                foreach ($existingImages as $existingImage) {
                    Storage::disk('local')->delete('public/penawaran/' . $existingImage->file);
                    $existingImage->delete();
                }
                foreach ($request->file('file') as $image) {
                    $imageContent = file_get_contents($image->getRealPath());
                    $imageName = uniqid() . '_' . $image->getClientOriginalName();
                    Storage::disk('local')->put("public/penawaran/{$imageName}", $imageContent);
                    FilePenawaranProjects::create([
                        'penawaran_project_id' => $penawaran->id,
                        'file' => $imageName,
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('penawaran_projects')->with('status', 'berhasil mengubah data survey');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('penawaran_projects')->with('error', 'gagal memperbarui data survey');
        }
    }

    public function store(PenawaranProjectCreateRequest $request)
    {
        $data = $request->validated();
        $penawaran = new PenawaranProject($data);
        $penawaran->prospect_id = $data['prospect_id'];
        $penawaran->save();
        if (isset($data['prospect_id'])) {
            $prospect = Prospect::find($data['prospect_id']);
            if ($prospect) {
                $prospect->status = 'penawaran';
                $prospect->save();
            }
        }
        return response()->json(['message' => 'Penawaran created successfully.'], 200);
    }
    /**
     * Delete penawaran project
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penawaran = PenawaranProject::findOrFail($id);
            if (!empty($penawaran->file_excel)) {
                Storage::disk('local')->delete('public/excel/' . $penawaran->file_excel);
            }
            if (!empty($penawaran->file_pdf)) {
                Storage::disk('local')->delete('public/pdf/' . $penawaran->file_pdf);
            }
            $penawaran->delete();
            DB::commit();
            Log::info('Penawaran Deleted Successfully', ['id' => $id]);
            return response()->json([
                'message' => 'Penawaran berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Penawaran Deletion Failed:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'error' => 'Gagal menghapus penawaran',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadFile($id, $type)
    {
        $penawaran = PenawaranProject::findOrFail($id);
        $folderPath = $type === 'pdf' ? 'public/pdf' : 'public/excel';
        $fileName = $type === 'pdf' ? $penawaran->file_pdf : $penawaran->file_excel;
        $filePath = "{$folderPath}/{$fileName}";
        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return Storage::download($filePath);
    }
}
