<?php

namespace App\Filament\Resources\MovimientoResource\Pages;

use App\Filament\Resources\MovimientoResource;

use Filament\Actions;

use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Components\Tab;

use App\Models\Movimiento;

use Illuminate\Database\Eloquent\Builder;

use Filament\Notifications\Notification;

class ListMovimientos extends ListRecords
{
    protected static string $resource = MovimientoResource::class;

    /**
     * Acción crear
     */

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make()
                ->after(function () {

                    Notification::make()
                        ->title('Movimiento creado correctamente')
                        ->success()
                        ->send();

                }),

        ];
    }

    /**
     * Tabs con contador
     */

    public function getTabs(): array
    {
        return [

            'todos' => Tab::make('Todos')
                ->badge(Movimiento::count()),

            'ingresos' => Tab::make('Ingresos')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('tipo', 'ingreso')
                )
                ->badge(
                    Movimiento::where('tipo', 'ingreso')->count()
                ),

            'egresos' => Tab::make('Egresos')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('tipo', 'egreso')
                )
                ->badge(
                    Movimiento::where('tipo', 'egreso')->count()
                ),

        ];
    }
}