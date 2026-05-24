<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function plans()
    {
        return $this->hasMany(SportPlan::class);
    }
}
