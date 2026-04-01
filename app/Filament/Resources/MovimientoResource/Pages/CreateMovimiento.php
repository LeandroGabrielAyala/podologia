<?php

namespace App\Filament\Resources\MovimientoResource\Pages;

use App\Filament\Resources\MovimientoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateMovimiento extends CreateRecord
{
    protected static string $resource = MovimientoResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Movimiento creado')
            ->body('El movimiento fue registrado exitosamente.')
            ->success();
    }

    public function getTitle(): string
    {
        return 'Nuevo movimiento';
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
