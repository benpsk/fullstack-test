<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    public function photo(): HasOne
    {
        return $this->hasOne(Photo::class);
    }
}
