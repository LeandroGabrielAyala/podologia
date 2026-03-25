<?php

namespace App\Filament\Resources\ResumenMensualResource\Pages;

use App\Filament\Resources\ResumenMensualResource;

use Filament\Resources\Pages\ViewRecord;

use Filament\Infolists\Infolist;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;

use App\Models\Movimiento;

class ViewResumenMensual extends ViewRecord
{
    protected static string $resource = ResumenMensualResource::class;

    /**
     * INFOLIST COMPLETO
     */

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist

            ->schema([

                /**
                 * RESUMEN GENERAL
                 */

                Section::make('Resumen del Mes')

                    ->schema([

                        TextEntry::make('mes')
                            ->label('Mes')
                            ->formatStateUsing(fn ($state) =>
                                \App\Models\ResumenMensual::nombreMes($state)
                            ),

                        TextEntry::make('anio')
                            ->label('Año'),

                        TextEntry::make('ingresos')
                            ->label('Total Ingresos')
                            ->money('ARS')
                            ->color('success'),

                        TextEntry::make('egresos')
                            ->label('Total Egresos')
                            ->money('ARS')
                            ->color('danger'),

                        TextEntry::make('balance')
                            ->label('Balance')
                            ->state(fn ($record) =>
                                $record->ingresos - $record->egresos
                            )
                            ->money('ARS'),

                    ])
                    ->columns(5),

                /**
                 * INGRESOS
                 */

                Section::make('Ingresos')

                    ->schema([

                        RepeatableEntry::make('ingresos_lista')

                            ->schema([

                                TextEntry::make('concepto.nombre')
                                    ->label('Concepto'),

                                TextEntry::make('monto')
                                    ->label('Monto')
                                    ->money('ARS'),

                                TextEntry::make('fecha')
                                    ->date('d/m/Y'),

                            ])
                            ->columns(3)

                    ])

                    ->state(function ($record) {

                        return Movimiento::query()

                            ->whereYear('fecha', $record->anio)

                            ->whereMonth('fecha', $record->mes)

                            ->where('tipo', 'ingreso')

                            ->with('concepto')

                            ->orderBy('fecha')

                            ->get();

                    }),

                /**
                 * EGRESOS
                 */

                Section::make('Egresos')

                    ->schema([

                        RepeatableEntry::make('egresos_lista')

                            ->schema([

                                TextEntry::make('concepto.nombre')
                                    ->label('Concepto'),

                                TextEntry::make('monto')
                                    ->label('Monto')
                                    ->money('ARS'),

                                TextEntry::make('fecha')
                                    ->date('d/m/Y'),

                            ])
                            ->columns(3)

                    ])

                    ->state(function ($record) {

                        return Movimiento::query()

                            ->whereYear('fecha', $record->anio)

                            ->whereMonth('fecha', $record->mes)

                            ->where('tipo', 'egreso')

                            ->with('concepto')

                            ->orderBy('fecha')

                            ->get();

                    }),

            ]);
    }
}