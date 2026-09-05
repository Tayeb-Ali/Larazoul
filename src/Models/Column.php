<?php

namespace Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Column extends Model
{
    protected $fillable = [
        'name', 'modifiers', 'column', 'module_id', 'multi_lang',
    ];

    /**
     * Set the column name.
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = Str::snake(trim($value));
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function details(): HasOne
    {
        return $this->hasOne(ColumnDetail::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return Str::studly($this->name);
    }
}