<?php

namespace App\Filament\Resources\TransferBarangResource\Pages;

use App\Filament\Resources\TransferBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransferBarang extends EditRecord
{
    protected static string $resource = TransferBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
