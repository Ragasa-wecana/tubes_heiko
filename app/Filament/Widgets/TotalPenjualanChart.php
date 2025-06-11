<?php 
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;

class TotalPenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Total Penjualan';

    protected function getData(): array
    {
        $data = Penjualan::query()
            ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
            ->join('barang', 'penjualan_barang.barang_id', '=', 'barang.id')
            ->where('penjualan.status', 'bayar')
            ->selectRaw('barang.nama_barang, SUM(penjualan_barang.harga_jual * penjualan_barang.jml) as total_penjualan')
            ->groupBy('barang.nama_barang')
            ->get()
            ->map(function ($penjualan) {
                return [
                    'nama_barang' => $penjualan->nama_barang,
                    'total_penjualan' => $penjualan->total_penjualan,
                ];
            });

        if ($data->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Buat array warna berbeda untuk tiap item
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#66BB6A', '#BA68C8',
            '#F06292', '#81D4FA', '#AED581', '#D4E157',
            '#FF7043', '#90A4AE'
        ];

        $itemCount = $data->count();
        $backgroundColors = array_slice($colors, 0, $itemCount);

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data->pluck('total_penjualan')->toArray(),
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $data->pluck('nama_barang')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
