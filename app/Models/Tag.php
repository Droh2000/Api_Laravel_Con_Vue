<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /*public function posts()
    {
        // Es la misma relacion ya que por la tercera tabla tenemos la columna que le corresponde
        // para los Posts en este caso
        return $this->belongsToMany(Post::class);
    }*/

    // Relacion polimorfica 
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
