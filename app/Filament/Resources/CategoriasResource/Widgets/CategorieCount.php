<?php

namespace App\Filament\Resources\CategoriasResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Categorie;
use Filament\Support\Enums\IconPosition;
use App\Models\Product;

class CategorieCount extends BaseWidget
{
    protected int | string | array $columnSpan = "full"; // Para que ocupe todo el ancho del dashboard
    protected function getCards(): array
    {
        return [
            Stat::make('Categorías', Categorie::count())
                ->description('Total registradas')
                ->descriptionIcon('heroicon-o-tag', IconPosition::Before)
                ->color('info'),
            Stat::make('Productos creados', Product::count())
                ->descriptionIcon('heroicon-o-archive-box', IconPosition::Before)
                ->description('Cantidad total')
                ->color('info'),
                Stat::make('Unidades en stock', Product::sum('quantity'))
                ->descriptionIcon('heroicon-o-archive-box', IconPosition::Before)
                ->description('Stock total')
                ->color('success'),
                Stat::make(
                    'Valor total del inventario',
                    '$' . number_format(
                        Product::all()->sum(fn ($product) => $product->price * $product->quantity),
                        0, // sin decimales
                        ',', // separador decimal
                        '.' // separador de miles
                    )
                )
                    ->description('Valor total')
                    ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                    ->color('success')
        ];
    }
}
