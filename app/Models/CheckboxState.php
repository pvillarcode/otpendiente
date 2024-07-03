<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckboxState extends Model
{
    protected $connection = 'sqlite'; // Conexión a SQLite
    protected $table = 'checkbox_states'; // Nombre de la tabla en SQLite
    protected $primaryKey = 'codigo'; // Clave primaria en SQLite
    protected $fillable = ['codigo', 'corte', 'pulido', 'perforado','estado','pintado']; // Campos llenables
    public $timestamps = true;

    protected $casts = [
        'codigo' => 'string',
        'corte' => 'boolean',
        'pulido' => 'boolean',
        'perforado' => 'boolean',
        'pintado' => 'boolean',
        'estado' => 'string'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            \Log::info("Saving CheckboxState: " . $model->toJson());
        });

        static::saved(function ($model) {
            \Log::info("Saved CheckboxState: " . $model->toJson());
        });
    }
}