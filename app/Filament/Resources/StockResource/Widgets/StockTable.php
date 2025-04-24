<?php

namespace App\Filament\Resources\StockResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Product;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;

class StockTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full'; // Para que ocupe todo el ancho del dashboard

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()->orderBy('quantity', 'asc') // 🔽 Ordenar stock de menor a mayor
            )
            ->heading('Stock de productos')
            ->description('Si desea editar por completo un producto, dirigase a "Productos"')
            ->columns([
                ImageColumn::make('urlImage')
                    ->label('Imagen')
                    ->square(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->sortable()
                    ->color(fn($state) => match (true) {
                        $state < 5 => 'danger',   // rojo
                        $state < 10 => 'warning', // amarillo
                        default => 'success',     // verde
                    }),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('ARS', locale: 'es_AR')
                    ->sortable(),
                    TextColumn::make('total')
                    ->formatStateUsing(fn ($record) => $record->price * $record->quantity)
                    ->label('Precio Total')
                    ->money('ARS', locale: 'es_AR'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nombre')
                                ->required()
                                ->disabled(),

                            Forms\Components\FileUpload::make('urlImage')
                                ->label('Imagen')
                                ->image()
                                ->disabled(),

                            Forms\Components\TextInput::make('quantity')
                                ->label('Cantidad')
                                ->numeric()
                                ->required()
                                ->minValue(0),

                            Forms\Components\TextInput::make('price')
                                ->label('Precio')
                                ->numeric()
                                ->required()
                                ->minValue(0),
                        ]),
                    ])
                    ->modalHeading('Editar cantidad y precio'),
            ]);
    }
}
