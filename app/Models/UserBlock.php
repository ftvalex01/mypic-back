<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBlock extends Model
{
    // Si deseas habilitar la asignación masiva para ciertos campos, puedes descomentar y editar esta línea:
    // protected $fillable = ['user_id', 'blocked_user_id', 'block_date'];

    /**
     * Indica si el modelo debe usar marcas de tiempo.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $fillable = ['user_id', 'blocked_user_id', 'block_date'];

    /**
     * La relación con el usuario que realiza el bloqueo.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * La relación con el usuario que es bloqueado.
     */
    public function blockedUser()
    {
        return $this->belongsTo(User::class, 'blocked_user_id');
    }
}
