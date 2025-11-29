<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // 

class StoreSparePartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => [
                'required',
                'string',
                'max:100',
                // UBAHAN PRO: Abaikan SKU yang ada di tempat sampah (deleted_at != NULL)
                Rule::unique('spare_parts')->whereNull('deleted_at'),
            ],
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ];
    }
}