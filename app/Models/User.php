<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Para iniciar sesion por Tokens
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    // En este modelo para poder emplear la realcion con Spatie, por eso colocamos HashRole
    // este uso de los roles se coloca en las entidades y que sea de tipo Authenticado
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol' // Agregamos el Rol
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
    /*public function isAdmin(): bool{
        return $this->rol == 'admin';
    }*/

    // Funcion mutadora para convertir el Password encriptada
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    // Creamos esta funcion y dentro le colocamos todas las reglas que queramos poner
    // Aqui depende segun las necesidades, si un usuario es de tipo Admin por defecto ya deberia de tener el ROL de editor
    // o como aqui que pregunta si es de tipo aditor o administrador
    public function accessDashboard(): bool{
        // return $this->rol == 'admin';
        // El usuario Regular tambien podra acceder a esta opcion 
        return $this->hasRole('Editor') || $this->hasRole('Admin');
    }

}
