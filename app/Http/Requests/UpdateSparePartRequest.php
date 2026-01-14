<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSparePartRequest extends FormRequest
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
       // Mengambil ID sparepart dari parameter rute
        $sparePartId = $this->route('spare_part')->id;

        return [
            // Aturan 'unique' di sini akan mengabaikan record dengan ID saat ini
            // 'sku' => ['required', 'string', 'max:100', Rule::unique('spare_parts')->ignore($sparePartId)],
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
