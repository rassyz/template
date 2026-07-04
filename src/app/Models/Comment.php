<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_news_id',
        'name',
        'review'
    ];

    public function articleNews(): BelongsTo
    {
        return $this->belongsTo(ArticleNews::class, 'article_news_id');
    }

    public function replyComments()
    {
        return $this->hasMany(ReplyComment::class, 'comments_id');
    }
}
