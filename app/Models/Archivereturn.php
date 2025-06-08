<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archivereturn extends Model
{
    protected $fillable = [
        'archivelending_id',
        'return_officer_name',
        'return_officer_position',
        'return_name',
        'return_position',
        'return_organization',
        'return_address',
        'return_phone',
        'return_email',
        'return_id_number',
        'return_date',
        'return_note',
    ];
}
