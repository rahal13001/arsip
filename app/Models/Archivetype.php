<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivetype extends Model
{
    protected $fillable = [
        'archive_type',
    ];

    public function archive()
    {
        return $this->hasMany(Archive::class);
    }
}
