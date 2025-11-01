<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreLoginRequest extends FormRequest
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
      'email' => 'required|string|min:5|max:255|email:dns|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i',
      'password' => ['required', 'string', 'not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i', Password::min(8)->mixedCase()->numbers()->symbols()],
    ];
  }

  public function messages(): array
  {
    return [
      'email.required' => 'Email harus diisi.',
      'email.string' => 'Email harus berupa string.',
      'email.min' => 'Email harus terdiri dari minimal 5 karakter.',
      'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
      'email.email' => 'Format email tidak valid.',
      'email.dns' => 'Domain email tidak valid.',
      'email.not_in' => 'Email tidak terdaftar.',
      
      'password.required' => 'Password harus diisi.',
      'password.string' => 'Password harus berupa string.',
      'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
      'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
      'password.numbers' => 'Password harus mengandung angka.',
      'password.symbols' => 'Password harus mengandung simbol.',

      'email.not_regex' => 'Email tidak boleh mengandung tag HTML.',
      'password.not_regex' => 'Password tidak boleh mengandung tag HTML.',
    ];
  }
}
