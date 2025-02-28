<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class PutRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

            return [
                'name' => 'required|min:5|max:500',
                // Aqui accedemos a parametros de la ruta donde nombramos el parametro como "user" y le damos el ID
                'email' => 'required|min:5|max:500|unique:users,email,'.$this->route('user')->id,
                'password' => ['required', 'confirmed', Rules\Password::default()
                    ::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                ],
                'password_confirmation' => 'required'
            ];
    }
}
