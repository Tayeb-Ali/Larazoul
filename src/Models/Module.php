<?php

namespace Larazoul\Larazoul\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Module extends Model
{
    protected $fillable = [
        'name', 'admin', 'website', 'api',
    ];

    /**
     * Set the module name.
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = ucfirst(Str::camel(Str::plural(trim($value))));
    }

    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ColumnDetail::class);
    }

    public function relations(): HasMany
    {
        return $this->hasMany(Relation::class, 'module_from_id');
    }

    /**
     * The generated table name for this module.
     */
    public function tableName(): string
    {
        return Str::snake(Str::plural(strtolower($this->name)));
    }

    /**
     * The generated model class for this module.
     */
    public function modelClass(): string
    {
        return 'App\\Models\\' . $this->name;
    }
}