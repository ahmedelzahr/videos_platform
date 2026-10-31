<?php

namespace App\Models;

use Dom\Comment;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{

    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function convertedvideos(){
        return $this->hasMany(Convertedvideo::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function views(){
        return $this->hasMany(View::class);
    }

     public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
