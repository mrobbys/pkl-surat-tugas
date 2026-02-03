<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name' => trim($this->name),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('roles', 'name')
                    ->whereNotIn('name', ['super-admin']),
                'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/',
            ],
            'permissions' => [
                'required',
                'array',
                'min:1',
            ],
            'permissions.*' => [
                'required',
                'integer',
                'exists:permissions,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama role wajib diisi.',
            'name.string' => 'Nama role harus berupa teks.',
            'name.min' => 'Nama role minimal 3 karakter.',
            'name.max' => 'Nama role maksimal 50 karakter.',
            'name.unique' => 'Nama role sudah digunakan.',
            'name.regex' => 'Format nama role tidak valid. Gunakan huruf kecil, angka, dan tanda hubung (-).',

            'permissions.required' => 'Pilih minimal 1 permission.',
            'permissions.array' => 'Format permissions tidak valid.',
            'permissions.min' => 'Pilih minimal 1 permission.',
            'permissions.*.exists' => 'Permission tidak valid.',
        ];
    }
}
