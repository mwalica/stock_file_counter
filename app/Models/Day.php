<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Day extends Model
{
    protected $fillable = ['media_type_id', 'amount', 'date'];

    public function mediaType(): BelongsTo
    {
        return $this->belongsTo(MediaType::class);
    }

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'amount' => 'integer',
        ];
    }

    protected function dateFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->date->format('j M Y')
        );
    }

    protected function amountColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match (true) {
                    $this->amount > 10 => 'bg-orange-600',
                    $this->amount > 8 => 'bg-orange-600/90',
                    $this->amount > 6 => 'bg-orange-600/80',
                    $this->amount > 4 => 'bg-orange-600/70',
                    $this->amount > 2 => 'bg-orange-600/60',
                    $this->amount > 1 => 'bg-orange-600/50',
                    $this->amount === 1 => 'bg-orange-600/40',
                    default => 'bg-gray-100',
                };
            }
        );
    }
}
