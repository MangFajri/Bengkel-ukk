<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Pelanggan hanya bisa mendaftarkan plat nomor yang belum ada sama sekali
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|digits:4|before_or_equal:' . date('Y'),
            'engine_number' => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:100',
        ];
    }
}