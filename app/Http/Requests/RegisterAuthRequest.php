<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class RegisterAuthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Имя пользователя должно быть заполнено!',
            'name.max' => 'Имя пользователя не должно быть длинее, чем 100 знаков!',
            'email.required' => 'Email пользователя должен быть заполнен!',
            'email.max' => 'Email пользователя не должжен быть длинее, чем 150 знаков!',
            'email.email' => 'Email пользователя должен быть похох на email!',
            'email.unique' => 'В системе уже зарегистрировано пользователь с таким Email',
            'password.required' => 'Пароль должден быть указан',
            'password.min' => 'Пароль должен быть длиннее, чем 6 знаков',
            'password.confirmed' => 'Пароль должен быть подтвержден',
        ];
    }

//    protected function failedValidation(Validator $validator)
//    {
//        throw new HttpResponseException(
//            new JsonResponse($validator->messages(), 422)
//        );
//    }
}
