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
        $prospectDate = null;
        if ($this->has('prospect_id')) {
            $prospect = Prospect::find($this->input('prospect_id'));
            $prospectDate = $prospect ? $prospect->tanggal : null;
            Log::info("Prospect Date: " . $prospectDate);
        }
        return [
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($prospectDate) {
                    if ($prospectDate && $value < $prospectDate) {
                        $fail('The survey date cannot be before the prospect date.');
                    }
                },
            ],
            'prospect_id' => ['required', 'exists:prospects,id'],
            'survey_results' => ['required'],
            'image.*' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
