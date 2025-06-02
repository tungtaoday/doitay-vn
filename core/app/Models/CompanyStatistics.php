<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStatistics extends Model
{
    protected $table = 'company_statistics';

    protected $fillable = [
        'company_id',
        'views',
        'hires'
    ];

    /**
     * Get the company that owns the statistics.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Increment hires count
     */
    public function incrementHires()
    {
        $this->increment('hires');
    }
} 