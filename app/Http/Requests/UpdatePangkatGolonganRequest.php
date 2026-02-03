<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePangkatGolonganRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation (sanitize input)
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'pangkat' => trim($this->pangkat),
            'golongan' => strtoupper(trim($this->golongan)),
            'ruang' => strtolower(trim($this->ruang)),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pangkatGolonganId = $this->route('pangkat_golongan')->id;
        return [
            'pangkat' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('pangkat_golongans', 'pangkat')->ignore($pangkatGolonganId),
                'regex:/^[A-Za-z\s\-\/]+$/',
            ],
            'golongan' => [
                'required',
                'string',
                'min:1',
                'max:4',
                'regex:/^(I|II|III|IV)$/',
            ],
            'ruang' => [
                'required',
                'string',
                'size:1',
                'regex:/^[a-e]$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'pangkat.required' => 'Pangkat harus diisi.',
            'pangkat.string' => 'Pangkat harus berupa string.',
            'pangkat.min' => 'Pangkat minimal 3 karakter.',
            'pangkat.max' => 'Pangkat tidak boleh lebih dari 100 karakter.',
            'pangkat.unique' => 'Pangkat sudah ada.',
            'pangkat.regex' => 'Pangkat hanya boleh mengandung huruf, spasi, dash (-), dan slash (/).',

            'golongan.required' => 'Golongan harus diisi.',
            'golongan.string' => 'Golongan harus berupa string.',
            'golongan.min' => 'Golongan harus minimal 1 karakter.',
            'golongan.regex' => 'Golongan harus berupa angka romawi yang valid (I, II, III, IV).',

            'ruang.required' => 'Ruang harus diisi.',
            'ruang.string' => 'Ruang harus berupa string.',
            'ruang.size' => 'Ruang harus terdiri dari 1 karakter.',
            'ruang.regex' => 'Ruang harus berupa huruf kecil (a, b, c, d, e).',
        ];
    }
}
