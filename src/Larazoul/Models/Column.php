<?php

namespace Larazoul\Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Column extends Model
{

    protected $fillable = [
        'name' , 'modifiers' , 'column' ,'module_id','multi_lang'
    ];

    /**
     * Set the column name.
     *
     * @param  string  $value
     * @return void
     */

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::snake(trim($value));
    }

    public function module(){
        return $this->belongsTo(Module::class);
    }

    public function details(){
        return $this->hasOne(ColumnDetail::class);
    }

}
