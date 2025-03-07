<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Aqui tenemos que agregar la nuevo columna "user_id" (No importa el orden de donde lo coloquemos) 
    protected $fillable = ['title', 'slug', 'content', 'category_id', 'description', 'posted', 'image', 'user_id'];

    // Aqui ya habiamos hecho una relacion Uno a Muchos (Dentro de la categoria tenemos el metodo de HasMany)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Si fuera para el usuario se colocaria asi
    public function user()
    {
        // Cuando son archivos que estan al mismo nivel no se tiene que colocar el import mediante el USE
        return $this->belongsTo(User::class);
    }

    // Relacion de las etiquetas
    /*public function tags()
    {
        // Como tenemos de 0 a N etiquetas aqui es el Many
        return $this->belongsToMany(Tag::class);
    }*/

    // Relacion polimorfica (Esta es la relacion secundaria)
    public function tags()
    {   
        // Entre comilla le pasamos el nombre de la relacion que como el de la tabla es singular se la pasamos asi
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
