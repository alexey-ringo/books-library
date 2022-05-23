<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class LoginAuthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email пользователя должен быть заполнен!',
            'email.max' => 'Email пользователя не должжен быть длинее, чем 150 знаков!',
            'email.email' => 'Email пользователя должен быть похох на email!',
            'password.required' => 'Пароль должден быть указан',
        ];
    }

//    protected function failedValidation(Validator $validator)
//    {
//        throw new HttpResponseException(
//            new JsonResponse($validator->messages(), 422)
//        );
//    }
}
