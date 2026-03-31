<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Movimiento;

class FinanzasOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Totales generales

        $ingresos = Movimiento::where('tipo', 'ingreso')->sum('monto');

        $egresos = Movimiento::where('tipo', 'egreso')->sum('monto');

        $balance = $ingresos - $egresos;

        // Generar arrays de 12 meses

        $ingresosPorMes = array_fill(1, 12, 0);
        $egresosPorMes = array_fill(1, 12, 0);

        // Obtener ingresos agrupados

        $datosIngresos = Movimiento::query()
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->where('tipo', 'ingreso')
            ->whereYear('fecha', now()->year)
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        foreach ($datosIngresos as $mes => $total) {
            $ingresosPorMes[$mes] = $total;
        }

        // Obtener egresos agrupados

        $datosEgresos = Movimiento::query()
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->where('tipo', 'egreso')
            ->whereYear('fecha', now()->year)
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        foreach ($datosEgresos as $mes => $total) {
            $egresosPorMes[$mes] = $total;
        }

        return [

            Stat::make(
                'Ingresos Totales',
                '$ ' . number_format($ingresos, 2, ',', '.')
            )
                ->description('Ingresos del año')
                ->color('success')
                ->icon('heroicon-o-arrow-trending-up')
                ->chart(array_values($ingresosPorMes)),

            Stat::make(
                'Egresos Totales',
                '$ ' . number_format($egresos, 2, ',', '.')
            )
                ->description('Egresos del año')
                ->color('danger')
                ->icon('heroicon-o-arrow-trending-down')
                ->chart(array_values($egresosPorMes)),

            Stat::make(
                'Balance Total',
                '$ ' . number_format($balance, 2, ',', '.')
            )
                ->description('Balance general')
                ->color('primary')
                ->icon('heroicon-o-scale'),

        ];
    }
}