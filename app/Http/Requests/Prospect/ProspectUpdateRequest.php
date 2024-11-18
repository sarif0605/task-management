<?php

namespace App\Http\Requests\Prospect;

use Illuminate\Foundation\Http\FormRequest;

class ProspectUpdateRequest extends FormRequest
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
             'nama_produk' => 'nullable',
             'tanggal' => 'nullable|date',
             'pemilik' => 'nullable',
             'no_telp' => ['nullable', 'numeric', 'regex:/^62[0-9]+$/'],
             'lokasi' => [ 'nullable','url',
                            function ($attribute, $value, $fail) {
                                if (!str_contains($value, 'google.com/maps')) {
                                    $fail('Lokasi harus berupa URL Google Maps yang valid.');
                                }
                            },
                        ],
             'keterangan' => 'nullable',
             'status' => 'nullable'
        ];
    }
}
