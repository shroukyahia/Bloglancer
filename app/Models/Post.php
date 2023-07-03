<?php

namespace App\Models;

use App\Models\Category;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class);
    // }
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
