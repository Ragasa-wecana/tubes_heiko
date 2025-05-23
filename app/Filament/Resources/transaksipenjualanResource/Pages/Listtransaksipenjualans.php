<?php

namespace App\Filament\Resources\TransaksiPenjualanResource\Pages;

use App\Filament\Resources\transaksipenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiPenjualans extends ListRecords
{
    protected static string $resource = transaksipenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
