<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GenreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user('api')) {
            return true;
        }
        return false;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {       
        return [
            'title' => 'required|string|max:100',
        ];
    }
    
    public function messages()
    {
        return [
            'title.required' => 'Название книги должно быть заполнено!',
            'title.max' => 'Название книги не должно быть длинее, чем 100 знаков!',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            new JsonResponse($validator->messages(), 422)
        );
    }
}