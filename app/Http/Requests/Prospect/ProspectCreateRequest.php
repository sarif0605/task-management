<?php

namespace App\Http\Requests\Prospect;

use Illuminate\Foundation\Http\FormRequest;

class ProspectCreateRequest extends FormRequest
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
            'nama_produk' => 'required',
            'tanggal' => 'required|date',
            'no_telp' => ['required', 'numeric', 'regex:/^62[0-9]+$/'],
            'pemilik' => 'required',
            'lokasi' => [
                'nullable',
                'url',
                function ($attribute, $value, $fail) {
                    if ($value !== null && !str_contains($value, 'google.com/maps')) {
                        $fail('Lokasi harus berupa URL Google Maps yang valid.');
                    }
                },
            ],
            'keterangan' => 'required'
        ];
    }
}
