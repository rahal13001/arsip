<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Archivelending extends Model
{
    use Hasfactory;
    use HasUuids;
    protected $casts = [
        'date_input' => 'datetime', // This line is the magic!
    ];
    protected $fillable = [
        'archive_id',
        'officer_name',
        'officer_position',
        'applicant_name',
        'applicant_position',
        'applicant_organization',
        'applicant_address',
        'applicant_phone',
        'applicant_email',
        'applicant_id_number',
        'date_of_application',
        'date_of_approval',
        'date_of_lending',
        'lending_until',
        'application_note',
        'officer_note',
        'lending_approval',
        'return_date',
        'return_note',
        'return_officer_name',
        'returner_name',
        'returner_phone',

    ];

    public function archive() : BelongsTo
    {
        return $this->belongsTo(Archive::class);
    }

    public function archivereturn() : HasOne
    {
        return $this->hasOne(Archivereturn::class);
    }
}
