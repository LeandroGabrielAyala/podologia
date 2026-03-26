<?php

namespace App\Filament\Resources\ResumenMensualResource\Pages;

use App\Filament\Resources\ResumenMensualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResumenMensuals extends ListRecords
{
    protected static string $resource = ResumenMensualResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.resumen-mensuals.index') => 'Meses',
            '' => 'Lista'
        ];
    }

    public function getTitle(): string
    {
        return 'Resumenes mensuales';
    }
}
