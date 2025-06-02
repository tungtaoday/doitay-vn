<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolios';

    protected $fillable = [
        'company_id',
        'title',
        'image',
        'description',
    ];

    /**
     * Get the company that owns the portfolio.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}