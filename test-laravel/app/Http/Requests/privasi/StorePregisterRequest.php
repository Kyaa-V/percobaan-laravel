<?php

namespace App\Http\Requests\privasi;

use Illuminate\Foundation\Http\FormRequest;

class StorePregisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "emails" => "required|email",
            "schools" => "required|string",
            "photo" => "required|image",
            "SKL" => "required|image",
            "KTP" => "required|image",
            "AKTA_KELAHIRAN" => "required|image",
            "RAPORT" => "required|image",
            "NISN" => "required|string",
            "NPSN" => "required|string",
            "major" => "required|string",
            "PRESTASI" => "required|image",
            "status" => "required|string|in:PREDAFTAR,SISWA,LULUS",
            "users_id" => "required|string|max:36",
        ];
    }
}
