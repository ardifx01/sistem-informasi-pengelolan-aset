<?php

namespace App\Filament\Resources\RequestBarangResource\Pages;

use App\Filament\Resources\RequestBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRequestBarang extends CreateRecord
{
    protected static string $resource = RequestBarangResource::class;
     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
