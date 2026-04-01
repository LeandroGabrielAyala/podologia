<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConceptoResource\Pages;
use App\Models\Concepto;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;

use Filament\Notifications\Notification;

class ConceptoResource extends Resource
{
    protected static ?string $model = Concepto::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Conceptos';

    protected static ?string $navigationGroup = 'Configuración';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Badge contador
     */

    public static function getNavigationBadge(): ?string
    {
        return Concepto::count();
    }

    /**
     * FORM
     */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required(),

                Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'ingreso' => 'Ingreso',
                        'egreso' => 'Egreso',
                    ])
                    ->required(),

            ]);
    }

    /**
     * TABLE
     */

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('nombre')
                    ->label('Concepto')
                    ->searchable(),

                TextColumn::make('tipo')
                    ->badge()
                    ->color(fn ($state) =>
                        $state === 'ingreso'
                            ? 'success'
                            : 'danger'
                    ),

            ])

            ->actions([

                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make()
                    ->after(function () {

                        Notification::make()
                            ->title('Concepto actualizado')
                            ->success()
                            ->send();

                    }),

                Tables\Actions\DeleteAction::make(),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConceptos::route('/'),
            'create' => Pages\CreateConcepto::route('/create'),
            'edit' => Pages\EditConcepto::route('/{record}/edit'),
        ];
    }
}