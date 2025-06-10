<?php

namespace App\Filament\Exports;

use App\Models\Barang;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BarangExporter extends Exporter
{
    protected static ?string $model = Barang::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('kode_barang')->label('Kode Barang'),
            ExportColumn::make('nama_barang')->label('Nama Barang'),
            ExportColumn::make('kategori_barang'),
            ExportColumn::make('ukuran'),
            ExportColumn::make('harga_barang'),
            ExportColumn::make('stock'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your barang export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
