<?php

namespace Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColumnDetail extends Model
{
    protected $fillable = [
        'validation', 'transformer', 'admin_crud', 'site_crud',
        'html_type', 'column_id', 'module_id', 'admin_filter',
        'website_filter', 'custom_validation',
    ];

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}