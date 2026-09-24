<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'species_id', 'user_id'];
    
    public function species()
    {
        return $this->belongsTo(Species::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
