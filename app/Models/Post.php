<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
            // Elimina todos los comentarios relacionados
            $post->comments()->delete();

            // Elimina todas las reacciones relacionadas
            $post->reactions()->delete();

            // Si el post tiene media asociada, elimina también esa media
            if ($post->media) {
                // Si almacenas la ruta del archivo en la media, puedes descomentar la siguiente línea para eliminar el archivo físico
                // Storage::delete($post->media->url);

                $post->media->delete();
            }
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

    public function media(): HasOne
    {
        return $this->hasOne(Media::class);
    }

    // En tu modelo Post
public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}
public function hashtags()
{
    return $this->belongsToMany(Hashtag::class, 'hashtag_post', 'post_id', 'hashtag_id');
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
