<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;

class MaterialUpdateRequest extends FormRequest
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
            'operational_project_id' => 'nullable|exists:operational_projects,id',
            'tanggal' => 'nullable|date',
            'pekerjaan' => 'nullable',
            'material' => 'nullable',
            'priority' => 'nullable',
            'for_date' => 'nullable|date',
            'keterangan' => 'nullable',
        ];
    }
}
