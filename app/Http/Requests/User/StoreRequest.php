<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:5|max:500',
            'email' => 'required|min:5|max:500|unique:users',
            // Tenemos dos formas de agregar validaciones, una es como arriba con solo un String y la otra es en un array
            // en la cual podemos emplear clases 
            'password' => ['required', 'confirmed', Rules\Password::default()
                // Aqui definimos las reglas para el password que ya estan creados internamente en Laravel
                ::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised() // Para que no coloque passwords que se consideran inseguros para los estandares actuales
            ],
            'password_confirmation' => 'required'
        ];
    }
}
