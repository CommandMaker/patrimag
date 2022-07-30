<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'article_id', 'author_id', 'created_at', 'updated_at'];

    public function article ()
    {
        return $this->belongsTo(Article::class);
    }

    public function author ()
    {
        return $this->belongsTo(User::class);
    }
}
