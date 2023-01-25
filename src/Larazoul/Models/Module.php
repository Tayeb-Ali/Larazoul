<?php

namespace Larazoul\Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Module extends Model
{

    protected $fillable = [
        'name' , 'admin' , 'website' , 'api'
    ];

    /**
     * Set the module name.
     *
     * @param  string  $value
     * @return void
     */

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(Str::camel(Str::plural(trim($value))));
    }


    public function columns(){
        return $this->hasMany(Column::class);
    }

    public function details(){
        return $this->hasMany(ColumnDetail::class);
    }

}
