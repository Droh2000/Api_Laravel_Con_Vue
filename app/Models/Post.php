<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Aqui tenemos que agregar la nuevo columna "user_id" (No importa el orden de donde lo coloquemos) 
    protected $fillable = ['title', 'slug', 'content', 'category_id', 'description', 'posted', 'image', 'user_id'];

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
}
