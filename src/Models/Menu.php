<?php

namespace Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name', 'slug',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }
}