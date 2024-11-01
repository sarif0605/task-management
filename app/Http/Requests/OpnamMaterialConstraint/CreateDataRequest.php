<?php

namespace App\Http\Requests\OpnamMaterialConstraint;

use Illuminate\Foundation\Http\FormRequest;

class CreateDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'deal_project_id' => 'required|uuid',
            'opnams_date' => 'nullable|date',
            'opnams_pekerjaan' => 'nullable|string',
            'kasbon_opnam_id'  => 'nullable',
            'kasbon_nominal' => 'nullable|numeric',
            'kasbon_keterangan' => 'nullable|string',
            'materials_tanggal' => 'nullable|date',
            'materials_material' => 'nullable|string',
            'materials_pekerjaan' => 'nullable|string',
            'materials_priority' => 'nullable|string',
            'materials_fordate' => 'nullable|date',
            'materials_keterangan' => 'nullable|string',
            'constraints_tanggal' => 'nullable|date',
            'constraints_kendala' => 'nullable|string',
            'constraints_pekerjaan' => 'nullable|string',
            'constraints_progress' => 'nullable|string',
        ];
    }
}
