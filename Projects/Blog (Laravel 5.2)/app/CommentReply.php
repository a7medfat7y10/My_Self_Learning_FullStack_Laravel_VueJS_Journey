<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    //
    protected $fillable = [
        'comment_id',
        'author',
        'photo',
        'is_active',
        'email',
        'body'
    ];

    public function comment() {
        return $this->belongsTo('App\Comment');
    }
}
