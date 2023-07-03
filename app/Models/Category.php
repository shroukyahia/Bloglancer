<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
    ];
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
    // public function post()
    // {
    //     return $this->belongsTo(Post::class);
    // }
}
