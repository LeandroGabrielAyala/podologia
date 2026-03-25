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

        ->query(

            \App\Models\ResumenMensual::query()

                ->fromSub(

                    \App\Models\Movimiento::query()

                        ->selectRaw('

                            CONCAT(
                                YEAR(fecha),
                                "-",
                                LPAD(MONTH(fecha),2,"0")
                            ) as id,

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

                )

        )

            ->columns([

                TextColumn::make('mes')
                    ->label('Mes')
                    ->formatStateUsing(function ($state) {

                        return ResumenMensual::nombreMes($state);

                    }),

                TextColumn::make('anio')
                    ->label('Año'),

                TextColumn::make('ingresos')
                    ->label('Total Ingresos')
                    ->money('ARS')
                    ->color('success'),

                TextColumn::make('egresos')
                    ->label('Total Egresos')
                    ->money('ARS')
                    ->color('danger'),

                TextColumn::make('balance')
                    ->label('Balance')
                    ->state(fn ($record) =>
                        $record->ingresos - $record->egresos
                    )
                    ->money('ARS'),

            ])

            ->actions([

                Tables\Actions\ViewAction::make(),

            ]);
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListResumenMensuals::route('/'),
            'view' => Pages\ViewResumenMensual::route('/{record}'),

        ];
    }
}