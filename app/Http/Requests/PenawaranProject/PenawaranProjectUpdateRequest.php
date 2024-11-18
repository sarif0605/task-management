<?php

namespace App\Http\Requests\PenawaranProject;

use Illuminate\Foundation\Http\FormRequest;

class PenawaranProjectUpdateRequest extends FormRequest
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
            'pembuat_penawaran' => 'nullable|string|max:30',
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'file_excel' => 'nullable|file|mimes:xlsx,xls|max:10240',
        ];
    }
}
