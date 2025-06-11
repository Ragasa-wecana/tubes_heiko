<?php

namespace App\Filament\Resources\TransaksiPenggajianResource\Pages;

use App\Filament\Resources\TransaksiPenggajianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiPenggajians extends ListRecords
{
    protected static string $resource = TransaksiPenggajianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
