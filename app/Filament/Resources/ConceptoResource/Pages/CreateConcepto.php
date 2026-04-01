<?php

namespace App\Filament\Resources\ConceptoResource\Pages;

use App\Filament\Resources\ConceptoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateConcepto extends CreateRecord
{
    protected static string $resource = ConceptoResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Concepto creado')
            ->body('El concepto fue registrado exitosamente.')
            ->success();
    }

    public function getTitle(): string
    {
        return 'Nuevo concepto';
    }

    public function getBreadcrumb(): string
    {
        return 'Crear';
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Crear');
    }

    protected function getCreateAnotherFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Crear otro');
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Cancelar');
    }
}
