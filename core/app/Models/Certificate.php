<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'certificates';

    protected $fillable = [
        'company_id',
        'name',
        'year',
    ];

    /**
     * Get the company that owns the certificate.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
