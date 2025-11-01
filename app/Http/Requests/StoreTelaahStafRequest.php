<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTelaahStafRequest extends FormRequest
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
            'kepada_yth' => 'required|string|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i|min:3|max:255',
            'dari' => 'required|string|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i|min:3|max:255',
            'nomor_telaahan' => 'required|string|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i|min:3|max:100',
            'tanggal_telaahan' => 'required|date',
            'perihal_kegiatan' => 'required|string|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i|min:3',
            'tempat_pelaksanaan' => 'required|string|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i|min:3|max:255',
            'tanggal_mulai' => 'required|date|before_or_equal:tanggal_selesai',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'dasar_telaahan' => 'required|string|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i|min:10',
            'isi_telaahan' => 'required|string|not_regex:/<\s*script\b[^>]*>(.*?)<\s*\/\s*script>/i|min:10',
            'pegawais' => 'required|array|min:1|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'kepada_yth.required' => 'Kepada Yth wajib diisi.',
            'kepada_yth.string' => 'Kepada Yth harus berupa string.',
            'kepada_yth.min' => 'Kepada Yth harus terdiri dari minimal 3 karakter.',
            'kepada_yth.max' => 'Kepada Yth tidak boleh lebih dari 255 karakter.',

            'dari.required' => 'Dari wajib diisi.',
            'dari.string' => 'Dari harus berupa string.',
            'dari.min' => 'Dari harus terdiri dari minimal 3 karakter.',
            'dari.max' => 'Dari tidak boleh lebih dari 255 karakter.',

            'nomor_telaahan.required' => 'Nomor telaahan wajib diisi.',
            'nomor_telaahan.string' => 'Nomor telaahan harus berupa string.',
            'nomor_telaahan.min' => 'Nomor telaahan harus terdiri dari minimal 3 karakter.',
            'nomor_telaahan.max' => 'Nomor telaahan tidak boleh lebih dari 100 karakter.',

            'tanggal_telaahan.required' => 'Tanggal telaahan wajib diisi.',
            'tanggal_telaahan.date' => 'Format tanggal telaahan tidak valid.',

            'perihal_kegiatan.required' => 'Perihal kegiatan wajib diisi.',
            'perihal_kegiatan.string' => 'Perihal kegiatan harus berupa string.',
            'perihal_kegiatan.min' => 'Perihal kegiatan harus terdiri dari minimal 3 karakter.',

            'tempat_pelaksanaan.required' => 'Tempat pelaksanaan wajib diisi.',
            'tempat_pelaksanaan.string' => 'Tempat pelaksanaan harus berupa string.',
            'tempat_pelaksanaan.min' => 'Tempat pelaksanaan harus terdiri dari minimal 3 karakter.',
            'tempat_pelaksanaan.max' => 'Tempat pelaksanaan tidak boleh lebih dari 255 karakter.',

            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_mulai.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal selesai.',

            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'dasar_telaahan.required' => 'Dasar telaahan wajib diisi.',
            'dasar_telaahan.string' => 'Dasar telaahan harus berupa string.',
            'dasar_telaahan.min' => 'Dasar telaahan harus terdiri dari minimal 10 karakter.',

            'isi_telaahan.required' => 'Isi telaahan wajib diisi.',
            'isi_telaahan.string' => 'Isi telaahan harus berupa string.',
            'isi_telaahan.min' => 'Isi telaahan harus terdiri dari minimal 10 karakter.',
            
            'pegawais.required' => 'Pilih minimal 1 pegawai.',
            'pegawais.array' => 'Pegawai harus berupa array.',
            'pegawais.min' => 'Pilih minimal 1 pegawai.',
            'pegawais.exists' => 'Pegawai yang dipilih tidak valid.',
            
            'not_regex' => 'Input mengandung karakter terlarang.',
        ];
    }
}
