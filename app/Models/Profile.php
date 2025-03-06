<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    // Este es el que tiene el metodo de "belobelongsTo" porque es el que tiene el FK (La columna relacional)
    // El nombre de la funcion es cualquiera pero usualmente se pone el nombre de la columna sin identificador
    // Si pusieramos los metodos invertidos obtendriamos un NULL porque no esta bien la relacion
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
