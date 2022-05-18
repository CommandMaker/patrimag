<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $content
 * @property string $author
 * @property integer $likes
 * @property integer $dislikes
 * @property string $created_at
 * @property string $updated_at
 */
class Article extends Model
{
    use HasFactory;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['title', 'slug', 'description', 'image', 'content', 'author', 'likes', 'dislikes', 'created_at', 'updated_at'];

}
