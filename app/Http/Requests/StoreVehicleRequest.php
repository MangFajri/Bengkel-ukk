<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; 

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'plate_number' => [
                'required',
                'string',
                'max:20',
                // LOGIKA PRO: Plat nomor boleh sama KALO data lama sudah dihapus
                Rule::unique('vehicles')->whereNull('deleted_at'),
            ],
            'engine_number' => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'year' => 'nullable|integer|digits:4',
        ];
    }
}