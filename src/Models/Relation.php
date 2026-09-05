<?php

namespace Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relation extends Model
{
    protected $fillable = [
        'module_from_id', 'module_to_id', 'column_id', 'type', 'input_type',
    ];

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    public function moduleFrom(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_from_id');
    }

    public function moduleTo(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_to_id');
    }
}