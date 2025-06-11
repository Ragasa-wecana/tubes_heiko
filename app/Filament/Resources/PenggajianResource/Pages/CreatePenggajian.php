<?php

namespace App\Filament\Resources\PenggajianResource\Pages;

use App\Filament\Resources\PenggajianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenggajian extends CreateRecord
{
    protected static string $resource = PenggajianResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_gaji'] = $data['gaji_pokok'] - $data['potongan_gaji'];
        return $data;
    }
}