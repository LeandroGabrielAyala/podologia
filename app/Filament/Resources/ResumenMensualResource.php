<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResumenMensualResource\Pages;
use App\Models\Movimiento;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;

use App\Models\ResumenMensual;

class ResumenMensualResource extends Resource
{
    /**
     * IMPORTANTE:
     * Usar modelo ResumenMensual
     */

    protected static ?string $model = ResumenMensual::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Meses';

    protected static ?string $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 2;

    /**
     * TABLA
     */

    public static function table(Table $table): Table
    {
        return $table

            ->defaultSort('id', 'desc')

            ->columns([

                TextColumn::make('mes')
                    ->label('Mes')
                    ->formatStateUsing(fn ($state) =>
                        ResumenMensual::nombreMes($state)
                    ),

                TextColumn::make('anio')
                    ->label('Año'),

                TextColumn::make('ingresos')
                    ->label('Total Ingresos')
                    ->money('ARS')
                    ->badge()
                    ->color('success'),

                TextColumn::make('egresos')
                    ->label('Total Egresos')
                    ->money('ARS')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('balance')
                    ->label('Balance')
                    ->state(fn ($record) =>
                        $record->ingresos - $record->egresos
                    )
                    ->money('ARS')
                    ->badge()
                    ->color('info'),

            ])

            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver')

                    ->record(fn ($record) => $record) // ⭐ ESTA LÍNEA SOLUCIONA EL ERROR

                    ->modalHeading(fn ($record) =>
                        'Resumen ' .
                        ResumenMensual::nombreMes($record->mes)
                        . ' ' . $record->anio
                    )

                    ->infolist([

                        \Filament\Infolists\Components\Section::make('Movimientos del mes')

                            ->schema([

                                \Filament\Infolists\Components\RepeatableEntry::make('')

                                    ->schema([

                                        \Filament\Infolists\Components\TextEntry::make('concepto.nombre')
                                            ->label('Concepto'),

                                        \Filament\Infolists\Components\TextEntry::make('tipo')
                                            ->label('Tipo')
                                            ->badge()
                                            ->color(fn ($state) =>
                                                $state === 'ingreso'
                                                    ? 'success'
                                                    : 'danger'
                                            ),

                                        \Filament\Infolists\Components\TextEntry::make('monto')
                                            ->label('Monto')
                                            ->money('ARS'),

                                    ])

                                    ->columns(3)

                                    ->state(function ($record) {

                                        return Movimiento::query()

                                            ->whereYear('fecha', $record->anio)
                                            ->whereMonth('fecha', $record->mes)

                                            ->with('concepto')

                                            ->get()

                                            ->toArray();

                                    }),

                            ]),

                        \Filament\Infolists\Components\Section::make('Totales')

                            ->columns(3)

                            ->schema([

                                \Filament\Infolists\Components\TextEntry::make('ingresos')
                                    ->label('Total Ingresos')
                                    ->money('ARS')
                                    ->badge()
                                    ->color('success'),

                                \Filament\Infolists\Components\TextEntry::make('egresos')
                                    ->label('Total Egresos')
                                    ->money('ARS')
                                    ->badge()
                                    ->color('danger'),

                                \Filament\Infolists\Components\TextEntry::make('balance')
                                    ->label('Balance')
                                    ->state(fn ($record) =>
                                        $record->ingresos - $record->egresos
                                    )
                                    ->money('ARS')
                                    ->badge()
                                    ->color('info'),

                            ]),

                    ])

            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return ResumenMensual::query()

            ->fromSub(

                Movimiento::query()

                    ->selectRaw('

                        DATE_FORMAT(fecha, "%Y-%m") as id,

                        YEAR(fecha) as anio,

                        MONTH(fecha) as mes,

                        SUM(
                            CASE
                                WHEN tipo = "ingreso"
                                THEN monto
                                ELSE 0
                            END
                        ) as ingresos,

                        SUM(
                            CASE
                                WHEN tipo = "egreso"
                                THEN monto
                                ELSE 0
                            END
                        ) as egresos

                    ')

                    ->groupByRaw('YEAR(fecha), MONTH(fecha)'),

                'resumen_mensual'

            );
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListResumenMensuals::route('/'),

        ];
    }
}