<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpnamMaterialConstraint\CreateDataRequest;
use App\Models\Constraints;
use App\Models\KasbonOpnams;
use App\Models\Materials;
use App\Models\Opnams;

class OpnamMaterialConstraintController extends Controller
{
    public function createProject($deal_project_id)
    {
        return view('contractor.opnam_material_constraint.create', compact('deal_project_id'));
    }

    public function storeProjectData(CreateDataRequest $request)
    {
        $validatedData = $request->validated();
        $opnam = Opnams::create([
            'deal_project_id' => $validatedData['deal_project_id'],
            'date' => $validatedData['opnams_date'],
            'pekerjaan' => $validatedData['opnams_pekerjaan'],
        ]);

        KasbonOpnams::create([
            'opnam_id' => $opnam->id,
            'nominal' => $validatedData['kasbon_nominal'],
            'keterangan' => $validatedData['kasbon_keterangan'],
        ]);

        Materials::create([
            'deal_project_id' => $validatedData['deal_project_id'],
            'tanggal' => $validatedData['materials_tanggal'],
            'material' => $validatedData['materials_material'],
            'pekerjaan' => $validatedData['materials_pekerjaan'],
            'priority' => $validatedData['materials_priority'],
            'for_date' => $validatedData['materials_fordate'],
            'keterangan' => $validatedData['materials_keterangan'],
        ]);

        Constraints::create([
            'deal_project_id' => $validatedData['deal_project_id'],
            'tanggal' => $validatedData['constraints_tanggal'],
            'kendala' => $validatedData['constraints_kendala'],
            'pekerjaan' => $validatedData['constraints_pekerjaan'],
            'progress' => $validatedData['constraints_progress'],
        ]);
        return redirect()->route('deal_projects', $validatedData['deal_project_id'])->with('success', 'Data berhasil disimpan.');
    }

}
