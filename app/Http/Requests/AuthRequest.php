<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required | min:6 | max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email chưa được nhập',
            'email.email' => 'Email  không đúng định dạng, ví dụ abc@gmail.com',
            'password.required' => 'Password chưa được nhập',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 401,
            'data' => $validator->errors()
        ]));
    }
}
