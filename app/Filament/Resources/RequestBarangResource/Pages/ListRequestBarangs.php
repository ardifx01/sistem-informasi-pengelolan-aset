<?php

namespace App\Filament\Resources\RequestBarangResource\Pages;

use App\Filament\Resources\RequestBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestBarangs extends ListRecords
{
    protected static string $resource = RequestBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
