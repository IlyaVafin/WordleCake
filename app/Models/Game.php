<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        "category_id",
        "level",
        "increment_points",
        "decrement_points",
        "preview",
        "age_limit",
        "attempts"
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function words()
    {
        return $this->hasMany(Word::class);
    }
}
