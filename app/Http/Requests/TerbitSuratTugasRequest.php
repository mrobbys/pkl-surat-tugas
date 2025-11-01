<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TerbitSuratTugasRequest extends FormRequest
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
            'nomor_surat_tugas' => 'required|string|max:100',
            'dasar_surat_tugas' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_surat_tugas.required' => 'Nomor Surat Tugas wajib diisi.',
            'nomor_surat_tugas.string' => 'Nomor Surat Tugas harus berupa teks.',
            'nomor_surat_tugas.max' => 'Nomor Surat Tugas tidak boleh lebih dari 100 karakter.',
            
            'dasar_surat_tugas.required' => 'Dasar Surat Tugas wajib diisi.',
            'dasar_surat_tugas.string' => 'Dasar Surat Tugas harus berupa teks.',
        ];
    }
}
