<?php

namespace App\Filament\Resources\ResumenMensualResource\Pages;

use App\Filament\Resources\ResumenMensualResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResumenMensual extends EditRecord
{
    protected static string $resource = ResumenMensualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
