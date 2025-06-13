<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Archive extends Model
{
    use HasSlug;
    protected $with = ['archiveuser', 'archivelending', 'filecode', 'archivetype', 'accessclassification', 'archiveaccess'];

    protected $fillable = [
        'user_id',
        'filecode_id',
        'archivetype_id',
        'accessclassification_id',
        'archiveaccess_id',
        'archive_name',
        'archive_slug',
        'archive_number',
        'date_make',
        'date_input',
        'archive_description',
        'sheet_number',
        'storage_location',
        'development',
        'document',
        'document_number',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['archive_name', 'id'])
            ->saveSlugsTo('archive_slug');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function filecode() : BelongsTo
    {
        return $this->belongsTo(Filecode::class);
    }
    public function archivetype() : BelongsTo
    {
        return $this->belongsTo(Archivetype::class);
    }
    public function accessclassification() : BelongsTo
    {
        return $this->belongsTo(Accessclassification::class);
    }
    public function archiveuser() : HasOne
    {
        return $this->hasOne(Archiveuser::class);
    }
    public function archivelending() : HasMany
    {
        return $this->hasMany(Archivelending::class);
    }

    public function archiveaccess(): BelongsTo
    {
        return $this->belongsTo(Archiveaccess::class);
    }

    public function getRouteKeyName()
    {
        return 'archive_slug';
    }

}
