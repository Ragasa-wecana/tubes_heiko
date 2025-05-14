<?php

namespace App\Filament\Exports;

use App\Models\ListSupplier;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ListSupplierExporter extends Exporter
{
    protected static ?string $model = ListSupplier::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('kode_supplier')->label('Kode Supplier'),
            ExportColumn::make('nama_supplier')->label('Nama supplier'),
            ExportColumn::make('alamat'),
            ExportColumn::make('telepon'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your list supplier export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
