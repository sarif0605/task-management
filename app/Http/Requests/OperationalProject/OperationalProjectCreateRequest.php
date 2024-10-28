<?php

namespace App\Http\Requests\OperationalProject;

use Illuminate\Foundation\Http\FormRequest;

class OperationalProjectCreateRequest extends FormRequest
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
            'date' => 'required|date',
            'deal_project_id' => 'required|exists:deal_projects,id',
            'lokasi' => 'required',
            'keterangan' => 'required'
        ];
    }
}
