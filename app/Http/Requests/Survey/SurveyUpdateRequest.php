<?php

namespace App\Http\Requests\Survey;

use App\Models\Prospect;
use Illuminate\Foundation\Http\FormRequest;

class SurveyUpdateRequest extends FormRequest
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
    public function rules()
    {
        $prospectDate = null;
        if ($this->has('prospect_id')) {
            $prospect = Prospect::find($this->input('prospect_id'));
            $prospectDate = $prospect ? $prospect->tanggal : null;
        }
        return [
            'date' => [
                'nullable',
                function ($attribute, $value, $fail) use ($prospectDate) {
                    if ($prospectDate && $value < $prospectDate) {
                        $fail('The survey date cannot be before the prospect date.');
                    }
                },
            ],
            'prospect_id' => ['nullable', 'exists:prospects,id'],
            'survey_results' => ['nullable'],
            'image.*' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'date.nullable' => 'Tanggal survey tidak boleh null.',
            'date.date' => 'Tanggal survey harus dalam format tanggal yang valid.',
            'images.*.image' => 'Setiap file harus berupa gambar.',
            'images.*.mimes' => 'Jenis file yang diizinkan adalah jpeg, png, jpg, gif.',
            'images.*.max' => 'Ukuran maksimum untuk setiap gambar adalah 2 MB.',
        ];
    }
}
