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
            'name' => 'required | max : 255',
            'price' => 'required | numeric',
            'image' => 'nullable | image | max : 2048',
            'brand_id' => 'required| integer | exists:brands,id',
            'category_id' => 'required | integer | exists:categories,id',
            'image_position' => 'array',
            'image_position.*' => 'integer',
            'images_to_delete' => 'array', // Kiểm tra mảng
            'images_to_delete.*' => 'integer|exists:product_images,id' //
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là một số.',
            'image.image' => 'Tệp phải là một hình ảnh.',
            'image.max' => 'Kích thước hình ảnh tối đa là 2MB.',
            'brand_id.required' => 'Thương hiệu là bắt buộc.',
            'brand_id.integer' => 'Thương hiệu phải là một số nguyên.',
            'brand_id.exists' => 'Thương hiệu không tồn tại.',
            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.integer' => 'Danh mục phải là một số nguyên.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'image_position.array' => 'Vị trí hình ảnh phải là một mảng.',
            'image_position.*.integer' => 'Vị trí hình ảnh phải là số nguyên.',
            'images_to_delete.array' => 'Hình ảnh cần xóa phải là một mảng.',
            'images_to_delete.*.integer' => 'Mỗi phần tử trong hình ảnh cần xóa phải là số nguyên.',
            'images_to_delete.*.exists' => 'Hình ảnh cần xóa không tồn tại.',
        ];
    }
}
