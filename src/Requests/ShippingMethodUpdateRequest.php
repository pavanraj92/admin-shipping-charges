<?php

namespace admin\shipping_charges\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingMethodUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100|unique:shipping_methods,name,' . $this->route('shipping_method')->id,
            'carrier' => 'nullable|string|max:100',
            'delivery_time' => 'nullable|string|max:100',
            'base_rate' => 'nullable|string|max:500',
            'zone_id' => 'required|string|min:3|max:65535',
            'status' => 'required|in:0,1', // Ensure status is one of the allowed values
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
