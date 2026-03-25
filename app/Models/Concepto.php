<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $fillable = [

        'nombre',
        'tipo',

    ];

    /**
     * Relación:
     * Un concepto tiene muchos movimientos
     */

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}