<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $author
 * @property integer $likes
 * @property integer $dislikes
 * @property string $created_at
 * @property string $updated_at
 */
class Article extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['title', 'content', 'author', 'likes', 'dislikes', 'created_at', 'updated_at'];
}
