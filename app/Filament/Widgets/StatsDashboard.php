<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Barang;
use App\Models\RequestBarang;
use App\Models\TransferBarang;

class StatsDashboard extends BaseWidget
{
    protected static ?string $pollingInterval = '5s';
    protected ?string $heading = 'Analytics';
    protected function getStats(): array
    {
        $user = auth()->user();

        $requestQuery  = \App\Models\RequestBarang::query();

        // Jika PIC → hanya lihat request miliknya & sesuai cabang
        if ($user && !$user->hasRole('GA')) {
            $requestQuery->where('user_id', $user->id);
        }

        $countBarangs = Barang::count();
        $countRequests = $requestQuery->count();
        $countTranfers = TransferBarang::count();
        return [
            Stat::make('Total Barang',  $countBarangs)
                ->icon('heroicon-o-rectangle-stack')
                ->description('barang'),
            Stat::make('Request Barang', $countRequests)
                ->icon('heroicon-o-cube')
                ->description('request'),
            Stat::make('Transfer Barang', $countTranfers)
                ->icon('heroicon-o-paper-airplane')
                ->description('transfer'),

        ];
    }
}
