<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingDetail extends Model
{
    protected $fillable = [
        'rating_id', 'feature_id', 'rating',
    ];

    // Mối quan hệ với rating (mỗi RatingDetail thuộc một Rating)
    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    // Mối quan hệ với feature (mỗi RatingDetail thuộc một Feature)
    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    public $timestamps = false;
}

