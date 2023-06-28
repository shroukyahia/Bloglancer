<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'category_id'];


    public function posts()
    {
        return $this->belongsTo(Post::class, 'category_id');
    }
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
