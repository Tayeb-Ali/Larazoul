<?php

namespace Larazoul\Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{

    protected $fillable = [
        'module_from_id' , 'module_to_id' , 'column_id' , 'type' , 'input_type'
    ];

    public function column(){
        return $this->belongsTo(Column::class);
    }

    public function module_from(){
        return $this->belongsTo(Module::class);
    }

    public function module_to(){
        return $this->belongsTo(Module::class);
    }

}
