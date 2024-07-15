<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckboxState extends Model
{
    protected $connection = 'sqlite'; // Conexión a SQLite
    protected $table = 'checkbox_states'; // Nombre de la tabla en SQLite
    protected $primaryKey = 'codigo'; // Clave primaria en SQLite
    protected $fillable = ['codigo', 'corte', 'pulido', 'perforado','estado','pintado','matriz','curvado','laminado','empavonado',
                        'disabled_codigo', 'disabled_corte', 'disabled_pulido', 'disabled_perforado','disabled_estado','disabled_pintado','disabled_matriz','disabled_curvado','disabled_laminado','disabled_empavonado']; // Campos llenables
    public $timestamps = true;

    protected $casts = [
        'codigo' => 'string',
        'corte' => 'boolean',
        'matriz' => 'boolean',
        'empavonado' => 'boolean',
        'laminado' => 'boolean',
        'curvado' => 'boolean',
        'pulido' => 'boolean',
        'perforado' => 'boolean',
        'pintado' => 'boolean',
        'estado' => 'string',
        'disabled_corte' => 'boolean',
        'disabled_matriz' => 'boolean',
        'disabled_empavonado' => 'boolean',
        'disabled_laminado' => 'boolean',
        'disabled_curvado' => 'boolean',
        'disabled_pulido' => 'boolean',
        'disabled_perforado' => 'boolean',
        'disabled_pintado' => 'boolean'
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