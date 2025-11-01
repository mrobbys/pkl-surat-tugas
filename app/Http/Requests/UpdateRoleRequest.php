<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
        $id = $this->role?->id ?? null;
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'unique:roles,name,' . $id,
                'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
                'not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i'
            ],
            'permissions' => 'required|min:1|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama role wajib diisi.',
            'name.string' => 'Nama role harus berupa string.',
            'name.min' => 'Nama role minimal 3 karakter.',
            'name.unique' => 'Nama role sudah digunakan.',
            'name.regex' => 'Nama role hanya boleh mengandung huruf kecil, angka, dan tanda hubung (-), serta tidak boleh diawali atau diakhiri dengan tanda hubung.',
            
            'permissions.required' => 'Permissions harus dipilih minimal 1.',
            'permissions.min' => 'Permissions harus dipilih minimal 1.',
            'permissions.array' => 'Permissions harus berupa array.',

            'not_regex' => 'Input mengandung karakter terlarang.'
            
        ];
    }
}
