<?php

namespace App\Http\Requests\PenawaranProject;

use Illuminate\Foundation\Http\FormRequest;

class PenawaranProjectCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prospect_id' => ['required','exists:prospects,id'],
        ];
    }
}
