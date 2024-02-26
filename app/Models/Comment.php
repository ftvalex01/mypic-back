<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'post_id', // Asegúrate de que este campo exista si no estás utilizando relaciones polimórficas.
        'text',
        // 'comment_date' si decides manejarlo automáticamente o permitir su asignación masiva.
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'comment_date' => 'timestamp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
    // En tu modelo Comment
public function reactions()
{
    return $this->morphMany(Reaction::class, 'reactable');
}

   /*  public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    } */
}
