<?php

namespace App\Http\Requests\DealProject;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'harga_deal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'users' => 'required|array|min:1',
            'users.*' => 'required|exists:users,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'prospect_id.required' => 'ID Prospect harus diisi',
            'prospect_id.exists' => 'Prospect tidak ditemukan',
            'date.required' => 'Tanggal deal harus diisi',
            'date.date' => 'Format tanggal tidak valid',
            'harga_deal.required' => 'Harga deal harus diisi',
            'harga_deal.numeric' => 'Harga deal harus berupa angka',
            'harga_deal.min' => 'Harga deal tidak boleh negatif',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter',
            'users.required' => 'Pilih minimal satu user',
            'users.array' => 'Format users tidak valid',
            'users.min' => 'Pilih minimal satu user',
            'users.*.exists' => 'Salah satu user yang dipilih tidak valid'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
