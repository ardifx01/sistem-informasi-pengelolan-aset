<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
{
    $category = \App\Models\Category::find($data['category_id']);
    $prefix = strtoupper(substr($category->name, 0, 2));

    $attempt = 0;
    $success = false;

    while (!$success && $attempt < 3) {
        try {
            \DB::transaction(function () use (&$data, $prefix) {
                $lastNumber = \App\Models\Barang::where('id', 'like', $prefix . '%')
                    ->lockForUpdate()
                    ->selectRaw("MAX(CAST(SUBSTRING(id, 3) AS UNSIGNED)) as max_number")
                    ->value('max_number');

                $nextNumber = $lastNumber ? $lastNumber + 1 : 1;
                $data['id'] = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            });

            $success = true; // Berhasil generate tanpa error
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                $attempt++; // Coba ulang generate
            } else {
                throw $e;
            }
        }
    }

    return $data;
}

    // override redirect setelah create
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
