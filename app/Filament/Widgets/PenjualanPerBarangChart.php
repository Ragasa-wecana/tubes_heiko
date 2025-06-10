<?php 
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;

class PenjualanPerBarangChart extends ChartWidget
{
    protected static ?string $heading = 'Total Penjualan per Barang';

    protected function getData(): array
    {
        $data = Penjualan::query()
            ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
            ->join('barang', 'penjualan_barang.barang_id', '=', 'barang.id')
            ->where('penjualan.status', 'bayar')
            ->selectRaw('barang.nama_barang, SUM(penjualan_barang.harga_jual * penjualan_barang.jml) as total_penjualan')
            ->groupBy('barang.nama_barang')
            ->get();

        if ($data->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data->pluck('total_penjualan')->toArray(),
                    'backgroundColor' => '#36A2EB', // Warna batang
                ],
            ],
            'labels' => $data->pluck('nama_barang')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Diagram batang
    }
}