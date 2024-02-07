<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'publish_date',
        'life_time',
        'permanent',
        'media_id',
    ];
    protected $appends = ['likesCount'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'publish_date' => 'timestamp',
        'permanent' => 'boolean',
    ];
    protected static function booted()
    {
        static::deleting(function ($post) {
            // Asegurarse de eliminar todos los comentarios y reacciones asociadas al post antes de eliminarlo
            $post->comments()->delete(); // Esto eliminará todos los comentarios relacionados
            $post->reactions()->delete(); // Esto eliminará todas las reacciones relacionadas
        });
    }
    public function getLikesCountAttribute()
    {
        // Asumiendo que quieres contar todas las reacciones para este post
        return $this->reactions()->count();
    }
    public function isLikedByUser($userId)
    {
        return $this->reactions()->where('user_id', $userId)->exists();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    // En tu modelo Post
public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}


public function reactions()
{
    return $this->morphMany(Reaction::class, 'reactable');
}

    public function interactionHistories(): HasMany
    {
        return $this->hasMany(InteractionHistory::class);
    }
}
