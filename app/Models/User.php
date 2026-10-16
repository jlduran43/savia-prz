<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'apellido',
        'rut',
        'email',
        'password',
        'departamento_id',
        'cargo_id',
        'jefe_id',
        'rol',
        'activo',
    ];

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

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function jefe()
    {
        return $this->belongsTo(User::class, 'jefe_id');
    }

    public function subordinados()
    {
        return $this->hasMany(User::class, 'jefe_id');
    }
}
