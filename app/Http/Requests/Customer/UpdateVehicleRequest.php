<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Mengambil ID kendaraan dari rute
        $vehicleId = $this->route('vehicle')->id;

        return [
            // Memastikan plat nomor unik, tapi mengabaikan plat nomor milik mobil ini sendiri
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles')->ignore($vehicleId)],
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|digits:4|before_or_equal:' . date('Y'),
            'engine_number' => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:100',
        ];
    }
}