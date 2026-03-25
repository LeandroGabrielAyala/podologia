<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Movimiento extends Model
{
    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [

        'tipo',           // ingreso | egreso
        'concepto_id',    // relación con conceptos
        'monto',
        'fecha',
        'descripcion',

    ];

    /**
     * Relación:
     * Un movimiento pertenece a un concepto.
     */
    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
    }

    /**
     * Scope:
     * Filtrar solo ingresos.
     */
    public function scopeIngresos(Builder $query)
    {
        return $query->where('tipo', 'ingreso');
    }

    /**
     * Scope:
     * Filtrar solo egresos.
     */
    public function scopeEgresos(Builder $query)
    {
        return $query->where('tipo', 'egreso');
    }

    /**
     * Accessor:
     * Devuelve mes del movimiento.
     * (Lo usaremos luego para reportes mensuales)
     */
    public function getMesAttribute()
    {
        return date('m', strtotime($this->fecha));
    }

    /**
     * Accessor:
     * Devuelve año del movimiento.
     */
    public function getAnioAttribute()
    {
        return date('Y', strtotime($this->fecha));
    }
}