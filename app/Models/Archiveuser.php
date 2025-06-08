<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Archiveuser extends Model
{
    protected $fillable = [
        'archive_id',
        'active_date',
        'inactive_date',
        'active_save_time',
        'inactive_save_time',
        'archive_properties',
        'archive_status',
        'destruction_date',
        'note'
    ];

    public function archive() : BelongsTo
    {
        return $this->belongsTo(Archive::class);
    }
}
