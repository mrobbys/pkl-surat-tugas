<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveTelaahStafRequest extends FormRequest
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
            'status' => 'required|in:disetujui,revisi,ditolak',
            'catatan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status persetujuan harus diisi.',
            'status.in' => 'Status persetujuan tidak valid.',
            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }
}
