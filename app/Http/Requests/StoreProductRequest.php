<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:255|unique:products',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'upc' => 'required|string|max:255|unique:products',
            'mpn' => 'required|string|max:255|unique:products',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ];
    }
}
