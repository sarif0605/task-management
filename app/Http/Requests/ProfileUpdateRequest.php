<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:16'],
            'birth_date' => ['required', 'date'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
