<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReplyComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comments_id',
        'name',
        'review'
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comments_id');
    }
}
