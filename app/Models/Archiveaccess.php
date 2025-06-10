<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Archiveaccess extends Model
{
        protected $fillable = [
        'archive_access',
        'access_limitation'];


}
