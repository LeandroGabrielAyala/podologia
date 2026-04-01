<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoResource\Pages;
use App\Models\Movimiento;
use App\Models\Concepto;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;

use Filament\Notifications\Notification;

class MovimientoResource extends Resource
{
    protected static ?string $model = Movimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Movimientos';

    protected static ?string $navigationGroup = 'Finanzas';

    protected static ?string $modelLabel = 'Movimiento';

    protected static ?string $pluralModelLabel = 'Movimientos';

    /**
     * Badge contador en menú
     */
    public static function getNavigationBadge(): ?string
    {
        return Movimiento::count();
    }

    /**
     * Color badge
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    /**
     * FORMULARIO
     */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'ingreso' => 'Ingreso',
                        'egreso' => 'Egreso',
                    ])
                    ->live()
                    ->required(),

                Select::make('concepto_id')
                    ->label('Concepto')
                    ->options(function (Forms\Get $get) {

                        $tipo = $get('tipo');

                        if (!$tipo) {
                            return [];
                        }

                        return \App\Models\Concepto::where('tipo', $tipo)
                            ->pluck('nombre', 'id');

                    })
                    ->searchable()
                    ->required(),

                TextInput::make('monto')
                    ->label('Monto')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                DatePicker::make('fecha')
                    ->label('Fecha')
                    ->required(),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull()

            ]);
    }

    /**
     * TABLA
     */

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn ($state) =>
                        $state === 'ingreso'
                            ? 'success'
                            : 'danger'
                    ),

                TextColumn::make('concepto.nombre')
                    ->label('Concepto')
                    ->searchable(),

                TextColumn::make('monto')
                    ->label('Monto')
                    ->money('ARS')
                    // ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

            ])

            ->actions([

                /**
                 * VIEW ACTION con INFOLIST
                 */

                Tables\Actions\ViewAction::make()
                    ->label('Ver')

                    ->modalHeading(fn ($record) =>
                        'Movimiento - $' . number_format($record->monto, 2, ',', '.')
                    )

                    ->infolist([

                        \Filament\Infolists\Components\Section::make('Detalle del movimiento')
                            ->columns(2) // ⭐ dos columnas

                            ->schema([

                                TextEntry::make('tipo')
                                    ->label('TIPO')
                                    ->badge()
                                    ->color(fn ($state) =>
                                        $state === 'ingreso'
                                            ? 'success'
                                            : 'danger'
                                    ),

                                TextEntry::make('concepto.nombre')
                                    ->label('CONCEPTO')
                                    ->badge('primary'),

                                TextEntry::make('monto')
                                    ->label('MONTO')
                                    ->money('ARS'),

                                TextEntry::make('fecha')
                                    ->label('FECHA')
                                    ->date('d/m/Y'),

                                TextEntry::make('descripcion')
                                    ->label('DESCRIPCIÓN')
                                    ->weight('italic')
                                    ->placeholder('Sin descripción')
                                    ->columnSpanFull(), // ⭐ ocupa todo el ancho

                            ]),

                    ]),

                /**
                 * EDIT
                 */

                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->after(function () {

                        Notification::make()
                            ->title('Movimiento actualizado correctamente')
                            ->success()
                            ->send();

                    }),

                /**
                 * DELETE
                 */

                Tables\Actions\DeleteAction::make()
                    ->label('Borrar')
                    ->modalHeading('Eliminar movimiento')
                    ->modalDescription('¿Estás seguro de eliminar este movimiento?')
                    ->modalSubmitActionLabel('Sí, eliminar')
                    ->after(function () {

                        Notification::make()
                            ->title('Movimiento eliminado correctamente')
                            ->success()
                            ->send();

                    }),

            ])

            ->bulkActions([

                Tables\Actions\DeleteBulkAction::make()->label('Eliminar seleccionados'),

            ]);
    }

    /**
     * RELACIONES
     */

    public static function getRelations(): array
    {
        return [];
    }

    /**
     * PÁGINAS
     */

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovimientos::route('/'),
            'create' => Pages\CreateMovimiento::route('/create'),
            'edit' => Pages\EditMovimiento::route('/{record}/edit'),
        ];
    }
}