<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingReaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'rating_id',
        'reaction_type_id',
    ];
    
    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }
    
    public function reactionType()
    {
        return $this->belongsTo(ReactionType::class);
    }

    
}
