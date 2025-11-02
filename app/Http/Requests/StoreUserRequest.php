<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'nip' => 'required|digits:18|unique:users,nip',
            'nama_lengkap' => 'required|string|regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s.,\'"-]*[A-Za-zÀ-ÿ.,]$/u|min:3|max:255',
            'email' => 'required|email:dns|unique:users,email',
            'password' => ['required', 'string', Password::min(8)->max(100)->mixedCase()->numbers()->symbols()],
            'roles' => 'required|array|min:1|exists:roles,id',
            'pangkat_golongan_id' => 'required|exists:pangkat_golongans,id',
            'jabatan' => 'required|string|min:3|max:255|regex:/^[a-zA-Z\s]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'nip.unique' => 'NIP sudah digunakan.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa string.',
            'nama_lengkap.regex' => 'Nama lengkap hanya boleh mengandung huruf, spasi, titik, dan koma.',
            'nama_lengkap.min' => 'Nama lengkap harus terdiri dari minimal 3 karakter.',
            'nama_lengkap.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.dns' => 'Domain email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa string.',
            'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
            'password.max' => 'Password tidak boleh lebih dari 100 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol.',

            'roles.required' => 'Role wajib dipilih.',
            'roles.array' => 'Role harus berupa array.',
            'roles.min' => 'Pilih minimal 1 role.',
            'roles.exists' => 'Role yang dipilih tidak valid.',

            'pangkat_golongan_id.required' => 'Pangkat/Golongan wajib dipilih.',
            'pangkat_golongan_id.exists' => 'Pangkat/Golongan yang dipilih tidak valid.',

            'jabatan.required' => 'Jabatan wajib diisi.',
            'jabatan.string' => 'Jabatan harus berupa string.',
            'jabatan.min' => 'Jabatan harus terdiri dari minimal 3 karakter.',
            'jabatan.max' => 'Jabatan tidak boleh lebih dari 255 karakter.',
            'jabatan.regex' => 'Jabatan hanya boleh berisi huruf dan spasi.',
        ];
    }
}
