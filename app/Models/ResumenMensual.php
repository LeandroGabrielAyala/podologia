<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumenMensual extends Model
{
    protected $table = 'movimientos';

    public $timestamps = false;

    protected $guarded = [];

    public static function nombreMes($mes)
    {
        $meses = [

            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',

        ];

        return $meses[$mes] ?? $mes;
    }
}