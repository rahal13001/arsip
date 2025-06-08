<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filecode extends Model
{
    protected $fillable = [
        'file_code',
        'code_name',
    ];

    public function archive() : Hasmany
    {
        return $this->hasMany(Archive::class);
    }

}
