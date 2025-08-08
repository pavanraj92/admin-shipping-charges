<?php

namespace admin\shipping_charges\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingRateUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'min_value' => 'required|numeric|min:0',
            'max_value' => 'required|numeric|min:0|gt:min_value',
            'rate' => 'required|numeric|min:0',
            'method_id' => 'required|exists:shipping_methods,id',
            'based_on' => 'required|in:weight,price,quantity',
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
