<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'article_id', 'author_id', 'created_at', 'updated_at', 'parent'];

    protected $with = ['author', 'replies'];

    /**
     * Return the article corresponding to the comment
     *
     * @return BelongsTo<Article, Comment>
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Return the author of the comment
     *
     * @return BelongsTo<User, Comment>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Comment>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent');
    }
}
