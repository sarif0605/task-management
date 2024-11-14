<?php

namespace App\Http\Requests\PenawaranProject;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PenawaranProjectCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pembuat_penawaran' => 'required|string|max:255',
            'prospect_id' => [
                'required',
                'string',
                'exists:prospects,id',
                function ($attribute, $value, $fail) {
                    $prospect = \App\Models\Prospect::find($value);
                    if ($prospect && $prospect->status === 'penawaran') {
                        $fail('Penawaran sudah dibuat untuk prospect ini.');
                    }
                },
            ],
            'file_pdf' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'file_excel' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ];
    }

    public function messages(): array
    {
        return [
            'pembuat_penawaran.required' => 'Nama pembuat penawaran wajib diisi.',
            'pembuat_penawaran.string' => 'Nama pembuat penawaran harus berupa teks.',
            'pembuat_penawaran.max' => 'Nama pembuat penawaran maksimal 255 karakter.',

            'prospect_id.required' => 'ID prospect wajib diisi.',
            'prospect_id.string' => 'Format ID prospect tidak valid.',
            'prospect_id.exists' => 'Prospect yang dipilih tidak ditemukan.',

            'file_pdf.required' => 'File PDF wajib diupload.',
            'file_pdf.file' => 'Upload PDF harus berupa file.',
            'file_pdf.mimes' => 'File harus berupa PDF.',
            'file_pdf.max' => 'Ukuran file PDF maksimal 10MB.',

            'file_excel.required' => 'File Excel wajib diupload.',
            'file_excel.file' => 'Upload Excel harus berupa file.',
            'file_excel.mimes' => 'File harus berupa Excel (xlsx atau xls).',
            'file_excel.max' => 'Ukuran file Excel maksimal 10MB.',
        ];
    }

    protected function prepareForValidation()
    {
        // Log incoming request data
        Log::info('Incoming Penawaran Project Request', $this->all());
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Log validation errors
        Log::error('Penawaran Project Validation Failed', [
            'errors' => $validator->errors()->toArray()
        ]);

        parent::failedValidation($validator);
    }
}
