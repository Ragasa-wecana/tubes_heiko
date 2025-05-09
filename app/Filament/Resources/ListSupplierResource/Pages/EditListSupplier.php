<?php

namespace App\Filament\Resources\ListSupplierResource\Pages;

use App\Filament\Resources\ListSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListSupplier extends EditRecord
{
    protected static string $resource = ListSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
