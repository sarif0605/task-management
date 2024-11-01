<?php

namespace App\Http\Requests\DealProject;

use Illuminate\Foundation\Http\FormRequest;

class DealProjectCreateRequest extends FormRequest
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
            'prospect_id' => 'required|exists:prospects,id',
            'date' => 'required|date',
            'price_quotation' => 'required|numeric',
            'nominal' => 'required|numeric',
            'lokasi' => 'nullable|string|max:200',
            'keterangan' => 'required|string',
            'users' => 'required|array'
        ];
    }
}
