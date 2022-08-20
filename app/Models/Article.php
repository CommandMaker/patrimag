<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $content
 * @property string $author
 * @property int $likes
 * @property int $dislikes
 * @property string $created_at
 * @property string $updated_at
 */
class Article extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array<string>
     */
    protected $fillable = ['title', 'slug', 'description', 'image', 'content', 'author_id', 'likes', 'dislikes', 'created_at', 'updated_at'];

    /**
     * Return the article's author
     *
     * @return BelongsTo<User, Article>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return all article's comments
     *
     * @return HasMany<Comment>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
