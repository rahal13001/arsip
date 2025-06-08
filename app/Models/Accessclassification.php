<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accessclassification extends Model
{
    protected $fillable = [
        'access_classification',
    ];

    public function archive() : Hasmany
    {
        return $this->hasMany(Archive::class);
    }
}
