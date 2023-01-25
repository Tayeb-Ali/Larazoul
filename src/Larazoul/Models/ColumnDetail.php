<?php

namespace Larazoul\Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;

class ColumnDetail extends Model
{
    protected $fillable = [
        'validation', 'transformer', 'admin_crud', 'site_crud', 'html_type', 'column_id', 'module_id','admin_filter','website_filter','custom_validation'
    ];

    public function column(){
        return $this->belongsTo(Column::class);
    }

    public function module(){
        return $this->belongsTo(Module::class);
    }
}
