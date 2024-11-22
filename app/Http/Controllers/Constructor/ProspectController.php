<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Prospect\ProspectCreateRequest;
use App\Http\Requests\Prospect\ProspectUpdateRequest;
use App\Models\Prospect;
use App\Exports\ProspectExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
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
        return redirect()->route('prospects')->with('success', 'Prospect berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $prospect = Prospect::find($id);
        if (!$prospect) {
            return redirect()->route('prospects')->with('error', 'prospect gagal dihapus');
        }
        $prospect->delete();
        return redirect()->route('prospects')->with('success', 'prospect berhasil dihapus');
    }
}
