<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'company_id', 'user_id', 'appointment_id', 'suggest', 'status','avg_rating',
    ];

    // Mối quan hệ một rating có nhiều rating_details
    public function ratingDetails()
    {
        return $this->hasMany(RatingDetail::class);
    }

    // Mối quan hệ với công ty
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
    return $this->belongsTo(User::class);
    }

    // Mối quan hệ với appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function reactions()
    {
    return $this->hasMany(RatingReaction::class);
    }

    // Relationship với features thông qua rating_details
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'rating_details')
                    ->withPivot('rating')
                    ->withTimestamps();
    }

}

