<?php

namespace App\Filament\Resources\ResumenMensualResource\Pages;

use App\Filament\Resources\ResumenMensualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResumenMensuals extends ListRecords
{
    protected static string $resource = ResumenMensualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
