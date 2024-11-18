<?php

namespace App\Http\Requests\DealProject;

use App\Models\Prospect;
use App\Models\Survey;
use Illuminate\Validation\Validator;
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
    public function rules()
    {
        return [
            'prospect_id' => 'nullable|exists:prospects,id',
            'date' => 'required|date',
            'harga_deal' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'users' => 'nullable|array|min:1',
            'users.*' => 'nullable|exists:users,id',
            'rab' => 'nullable|file|max:10240',
            'rap' => 'nullable|file|max:10240',
        ];
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $prospect = Prospect::find($this->input('prospect_id'));
            if ($prospect) {
                // Validasi tanggal DealProject tidak boleh sebelum tanggal Prospect
                if ($this->input('date') < $prospect->tanggal) {
                    $validator->errors()->add('date', 'Tanggal DealProject tidak boleh lebih awal dari tanggal Prospect.');
                }

                // Cek apakah ada survey terkait prospect ini
                $survey = Survey::where('prospect_id', $this->input('prospect_id'))->latest('date')->first();
                if ($survey && $this->input('date') < $survey->date) {
                    $validator->errors()->add('date', 'Tanggal DealProject tidak boleh lebih awal dari tanggal Survey terkait.');
                }
            }
        });
    }
}
