<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workplan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'workplans';

    /**
     * A feltölthető mezők listája.
     */
    protected $fillable = [
        'name',
        'company_id',
        'acs_id',
        'active',
    ];

    /**
     * Kapcsolat a cégekkel.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Kapcsolat az ACS rendszerekkel.
     */
    public function acsSystem()
    {
        return $this->belongsTo(ACS::class, 'acs_id');
    }
}
