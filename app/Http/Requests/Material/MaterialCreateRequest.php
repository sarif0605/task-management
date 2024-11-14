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
            'report_project_id' => 'required|exists:report_projects,id',
            'entries.*.tanggal' => 'required|date',
            'entries.*.pekerjaan' => 'required',
            'entries.*.material' => 'required',
            'entries.*.priority' => 'required',
            'entries.*.for_date' => 'required|date',
            'entries.*.keterangan' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'entries.*.tanggal.required' => 'Tanggal harus diisi.',
            'entries.*.tanggal.date' => 'Tanggal harus berformat tanggal.',
            'entries.*.pekerjaan.required' => 'Pekerjaan harus diisi.',
            'entries.*.material.required' => 'Material harus diisi.',
            'entries.*.priority.required' => 'Prioritas harus diisi.',
            'entries.*.for_date.required' => 'Tanggal harus diisi.',
            'entries.*.for_date.date' => 'Tanggal harus berformat tanggal.',
            'entries.*.keterangan.required' => 'Keterangan harus diisi.',
        ];
    }
}
