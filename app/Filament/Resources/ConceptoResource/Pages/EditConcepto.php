<?php

namespace App\Filament\Resources\ConceptoResource\Pages;

use App\Filament\Resources\ConceptoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditConcepto extends EditRecord
{
    protected static string $resource = ConceptoResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Concepto actualizado')
            ->body('Los cambios se guardaron correctamente.')
            ->success()
            ->duration(4000);
    }

    public function getTitle(): string
    {
        return "Editar concepto";
    }

    /**
     * Breadcrum
     */

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.conceptos.index') => 'Conceptos',
            '' => 'Editar',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Eliminar')
                ->modalHeading('Eliminar concepto')
                ->modalDescription('¿Está seguro de eliminar este concepto?')
                ->modalSubmitActionLabel('Sí, eliminar')
                ->modalCancelActionLabel('Cancelar')
                ->successNotificationTitle('concepto eliminado correctamente'),
        ];
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->label('Guardar cambios');
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Cancelar');
    }
}
