<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class Species extends Model
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
