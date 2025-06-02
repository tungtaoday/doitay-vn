<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    // Specify the table name if it does not follow Laravel's naming convention
    // protected $table = 'features';

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'id',
        'category_id',
        'name',
        'description',
        'status',
        'created_at',
        'updated_at'
    ];

    // Relationship to the Category model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function ratingDetails()
{
    return $this->hasMany(RatingDetail::class, 'feature_id', 'id');
}
}
