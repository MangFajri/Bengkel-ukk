<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
class UpdateUserRequest extends FormRequest
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
       // Mengambil ID pengguna dari parameter rute
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            // Aturan 'unique' di sini akan mengabaikan record dengan ID saat ini
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            // Password bersifat opsional saat update
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,mechanic,customer'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
