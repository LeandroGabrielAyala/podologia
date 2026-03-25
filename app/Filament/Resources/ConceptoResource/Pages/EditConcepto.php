<?php

namespace App\Filament\Resources\ConceptoResource\Pages;

use App\Filament\Resources\ConceptoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConcepto extends EditRecord
{
    protected static string $resource = ConceptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
