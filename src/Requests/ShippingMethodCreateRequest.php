<?php

namespace admin\shipping_charges\Requests;

use Illuminate\Foundation\Http\FormRequest;
use tidy;

class ShippingMethodCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:100|unique:shipping_methods,name',
            'carrier' => 'nullable|string|max:100',
            'delivery_time' => 'nullable|string|max:100',
            'base_rate' => 'nullable|numeric|min:0|max:99999999.99',
            'zone_id' => 'required|exists:shipping_zones,id',
            'status' => 'nullable|boolean',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
