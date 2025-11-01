<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePangkatGolonganRequest extends FormRequest
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
            'pangkat' => 'required|string|min:3|max:100|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i|unique:pangkat_golongans,pangkat',
            'golongan' => ['required', 'string', 'min:1', 'regex:/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/i', 'not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i'],
            'ruang' => 'required|string|max:1|regex:/^[a-z]+$/|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i',
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

            'golongan.required' => 'Golongan harus diisi.',
            'golongan.string' => 'Golongan harus berupa string.',
            'golongan.min' => 'Golongan minimal 1 karakter.',
            'golongan.regex' => 'Golongan harus berupa angka romawi yang valid (I, II, III, IV).',

            'ruang.required' => 'Ruang harus diisi.',
            'ruang.string' => 'Ruang harus berupa string.',
            'ruang.max' => 'Ruang tidak boleh lebih dari 1 karakter.',
            'ruang.regex' => 'Ruang harus berupa huruf kecil (a, b, c, d, dst).',

            'not_regex' => 'Input mengandung karakter terlarang.',
        ];
    }
}
