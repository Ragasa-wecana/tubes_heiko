<?php 
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;

use Carbon\Carbon;

class PieChartPenjualan extends ChartWidget
{
    protected static ?string $heading = 'Kontribusi Penjualan per Pembeli';

    protected function getData(): array
    {
        $data = Penjualan::query()
            ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
            ->join('pembeli', 'penjualan.pembeli_id', '=', 'pembeli_id')
            ->where('penjualan.status', 'bayar')
            ->selectRaw('pembeli.nama_pembeli, SUM(penjualan_barang.harga_jual * penjualan_barang.jml) as total_pembeli')
            ->groupBy('pembeli.nama_pembeli')
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
                    'label' => 'Total Penjualan per Pembeli',
                    'data' => $data->pluck('total_pembeli')->toArray(),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                ],
            ],
            'labels' => $data->pluck('nama_pembeli')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Bisa juga 'bar'
    }
}