<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class MediaType extends Model
{
    protected $fillable = ['name'];


    protected function badgeColor(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->name) {
                'photo' => 'blue',
                'video' => 'fuchsia',
                'music' => 'amber',
                default => 'zinc',
            }
        );
    }
}
