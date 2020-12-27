<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'author', 'picture', 'user_id'
    ];

    public function user() {
        //return $this->belongsTo(User::class, 'id');
        return $this->belongsTo(User::class);
    }

    public function genres() {
        return $this->belongsToMany(Genre::class,'book_genre');
    }
}
