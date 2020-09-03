<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = ['id', 'title', 'content', 'created_at'];

    public function author()
    {
    	return $this->belongsTo(Author::Class);
    }

    public function category()
    {
    	return $this->belongsTo(Category::Class);
    }
}
