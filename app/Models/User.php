<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Para iniciar sesion por Tokens

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Recordemos que un usuario puede tener multiples Posts por eso la relacion de hasMany
    function posts(){
        return $this->hasMany(Post::class);
    }

    // Anteriormente (Supongo que en el proyecto primor de laravel ya que aqui no venia la funcion)
    // Vimos como emplear un sistema de Roles que en este caso es bastante personalizado ya que modificamos
    // la entidad usuario para agregar este campo "ROL"  y de ahi agregar varias comparaciones
    // Este esquema nos puede servir para los casos en los que no requerimos un control tan exaustivo 
    // pero esto no nos sirve para los casos de roles y permisos
    public function isAdmin(): bool{
        return $this->rol == 'admin'
    }
}
