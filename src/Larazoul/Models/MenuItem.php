<?php

namespace Larazoul\Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{

    protected $fillable = [
        'name_en','name_ar','menu_id','parent_id','slug','order','icon','link'
    ];

    public function menu(){
        return $this->hasMany(Menu::class ,'menu_id');
    }

    public function parent(){
        return $this->hasMany(MenuItem::class , 'parent_id');
    }

    public function child(){
        return $this->belongsTo(MenuItem::class , 'parent_id');
    }

}
