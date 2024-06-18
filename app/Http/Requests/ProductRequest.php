<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required',
            'image' => 'nullable',
            'brand_id' => 'required',
            'category_id' => 'required',
            'image_position' => 'array',
            'image_position.*' => 'integer',
            'images_to_delete' => 'array', // Kiểm tra mảng
            'images_to_delete.*' => 'integer|exists:product_images,id' // Mỗi phần tử trong mảng phải là số nguyên và tồn tại trong bảng product_images
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'name is required',
            'price.required' => 'price is required',
            'image.required' => 'image is required',
            'brand_id.required' => 'brand_id is required',
            'category_id.required' => 'category_id is required',
        ];
    }
}
