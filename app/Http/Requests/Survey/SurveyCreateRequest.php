<?php

namespace App\Http\Requests\Survey;

use App\Models\Prospect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SurveyCreateRequest extends FormRequest
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
        return [
            'prospect_id' => ['required', 'exists:prospects,id']
        ];
    }
}
