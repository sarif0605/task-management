<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;

class MaterialCreateRequest extends FormRequest
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
            'operational_project_id' => 'required|exists:operational_projects,id',
            'tanggal' => 'required|date',
            'pekerjaan' => 'required',
            'material' => 'required',
            'priority' => 'required',
            'for_date' => 'required|date',
            'keterangan' => 'required',
        ];
    }
}
