<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateVehicleRequest extends FormRequest
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
         // Mengambil ID kendaraan dari parameter rute
        $vehicleId = $this->route('vehicle')->id;

        return [
            'user_id' => 'required|exists:users,id',
            // Aturan 'unique' akan mengabaikan plat nomor milik kendaraan yang sedang diedit
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles')->ignore($vehicleId)],
            'engine_number' => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'year' => 'nullable|integer|digits:4',
        ];
    }
}
