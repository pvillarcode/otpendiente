<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Albaranescli extends Model
{
    protected $connection = 'mysql';
    protected $table = 'albaranescli';

    public function lineas()
    {
        return $this->hasMany(LineaAlbaranCli::class, 'idalbaran', 'idalbaran');
    }

}
