<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $id = $this->id ?? 'NULL';

        return [
            'name' => 'required|max:255',
            'code' => 'required|max:50|unique:products,code,'.$id.',id,deleted_at,NULL',
            'price' => 'required',
            'product_category_id' => 'required'
        ];
    }
}
