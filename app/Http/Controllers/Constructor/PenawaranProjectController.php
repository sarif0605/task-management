<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenawaranProject\PenawaranProjectCreateRequest;
use App\Http\Requests\PenawaranProject\PenawaranProjectUpdateRequest;
use App\Models\PenawaranProject;
use App\Models\Prospect;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenawaranProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['isVerificationAccount', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $penawaran_project = PenawaranProject::with('prospect')->orderBy('created_at', 'DESC')->get();
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
        $penawaran_project = PenawaranProject::with('prospect')->find($id);
        if (!$penawaran_project) {
            return redirect()->route('penawaran_projects')
            ->with('error', 'Penawaran Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.penawaran_project.show', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penawaran_project = PenawaranProject::find($id);
        $prospect = Prospect::all();
        if (!$penawaran_project) {
            return redirect()->route('penawaran_projects.edit')
            ->with('error', 'Penawaran Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.survey.edit', compact('survey', 'prospect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PenawaranProjectUpdateRequest $request, string $id)
    {
        $penawaran_project = PenawaranProject::find($id);
        if (!$penawaran_project) {
            return redirect()->route('penawaran_projects.edit')
            ->with('error', 'Penawaran Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $penawaran_project->update($data);
        return redirect()->route('penawaran_projects')->with('success', 'Survey updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function store(PenawaranProjectCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $prospect = Prospect::findOrFail($data['prospect_id']);
            if ($prospect->status === 'penawaran') {
                throw new \Exception('Penawaran sudah dibuat untuk prospect ini.');
            }
            $penawaran_project = new PenawaranProject();
            $penawaran_project->pembuat_penawaran = $data['pembuat_penawaran'];
            $penawaran_project->prospect_id = $data['prospect_id'];
            if ($request->hasFile('file_pdf')) {
                try {
                    $cloudinaryPdf = $this->uploadToCloudinary(
                        $request->file('file_pdf'),
                        'bnp/pdfs'
                    );
                    $penawaran_project->file_pdf = $cloudinaryPdf['secure_path'];
                    $penawaran_project->pdf_public_id = $cloudinaryPdf['public_id'];
                } catch (\Exception $e) {
                    Log::error('PDF Upload Error:', [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                    throw new \Exception('Gagal mengupload file PDF: ' . $e->getMessage());
                }
            }
            if ($request->hasFile('file_excel')) {
                try {
                    $cloudinaryExcel = $this->uploadToCloudinary(
                        $request->file('file_excel'),
                        'bnp/excels'
                    );
                    $penawaran_project->file_excel = $cloudinaryExcel['secure_path'];
                    $penawaran_project->excel_public_id = $cloudinaryExcel['public_id'];
                } catch (\Exception $e) {
                    Log::error('Excel Upload Error:', [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                    throw new \Exception('Gagal mengupload file Excel: ' . $e->getMessage());
                }
            }
            $penawaran_project->save();
            $prospect->status = 'penawaran';
            $prospect->save();
            DB::commit();
            Log::info('Penawaran Creation Success', [
                'penawaran_id' => $penawaran_project->id,
                'prospect_id' => $prospect->id
            ]);
            return response()->json([
                'message' => 'Penawaran berhasil dibuat',
                'data' => $penawaran_project
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Penawaran Creation Failed:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $statusCode = $this->determineStatusCode($e);
            return response()->json([
                'error' => 'Gagal membuat penawaran',
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }

    /**
     * Upload file to Cloudinary
     */
    private function uploadToCloudinary($file, $folder)
    {
        $cloudinaryFile = $file->storeOnCloudinary($folder);
        if (!$cloudinaryFile) {
            throw new \Exception('Upload ke Cloudinary gagal');
        }
        return [
            'secure_path' => $cloudinaryFile->getSecurePath(),
            'public_id' => $cloudinaryFile->getPublicId()
        ];
    }

    /**
     * Determine appropriate HTTP status code based on exception
     */
    private function determineStatusCode(\Exception $e): int
    {
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return 404;
        }
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return 422;
        }
        return 500;
    }

    /**
     * Download penawaran file
     */
    public function download(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $penawaran_project = PenawaranProject::findOrFail($id);
        if ($type === 'pdf') {
            $filePath = $penawaran_project->file_pdf;
        } else {
            $filePath = $penawaran_project->file_excel;
        }
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"',
        ];
        return response()->download($filePath, basename($filePath), $headers);
    }

    /**
     * Delete penawaran project
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penawaran = PenawaranProject::findOrFail($id);

            // Delete files from Cloudinary
            if ($penawaran->pdf_public_id) {
                Cloudinary::destroy($penawaran->pdf_public_id);
            }
            if ($penawaran->excel_public_id) {
                Cloudinary::destroy($penawaran->excel_public_id);
            }
            $prospect = $penawaran->prospect;
            if ($prospect && $prospect->status === 'penawaran') {
                $prospect->status = 'new';
                $prospect->save();
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
}
