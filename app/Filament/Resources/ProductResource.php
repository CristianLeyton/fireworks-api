<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'producto';
    protected static ?string $pluralModelLabel = 'productos';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Tienda';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre: ')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('urlImage')
                    ->label('Imagen: ')
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU: ')
                    ->unique(Product::class, 'sku', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'El codigo SKU ya existe',
                    ])
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('price')
                    ->label('Precio: ')
                    ->required()
                    ->numeric()
                    ->prefix('ARS $'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad: ')
                    ->numeric()
                    ->default(null),
                    Forms\Components\Select::make('categorie_id')
                    ->label('Categoría: ')
                    ->relationship('categorie', 'name')
                    ->required()
                    ->createOptionModalHeading('Crear categoría')
                    ->createOptionForm(\App\Filament\Resources\CategorieResource::getFormSchema()),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción: ')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('urlImage')
                    ->label('Imagen')
                    ->square(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('Codigo SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('ARS', locale: 'es_AR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        $state < 5 => 'danger',   // rojo
                        $state < 10 => 'warning', // amarillo
                        default => 'success',     // verde
                    }),
                Tables\Columns\TextColumn::make('categorie.name')
                    ->label('Categoría')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Fecha de creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                ->label('Fecha de actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}
