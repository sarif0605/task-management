<?php

namespace App\Http\Requests\DealProject;

use Illuminate\Foundation\Http\FormRequest;

class DealProjectUpdateRequest extends FormRequest
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
            'date' => 'nullable|date',
            'prospect_id' => 'nullable|exists:prospects,id',
            'price_quotation' => 'nullable|numeric',
            'nominal' => 'nullable|numeric',
        ];
    }
}
