<?php
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;

class PenjualanPerPembeliChart extends ChartWidget
{
    protected static ?string $heading = null;

    public function getHeading(): string
    {
        return 'Total Penjualan per Pembeli ' . date('Y');
    }

    protected function getData(): array
    {
        $year = now()->year;

        // Ambil data total penjualan per pembeli
        $penjualanPerPembeli = Penjualan::query()
            ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
            ->join('pembeli', 'penjualan.pembeli_id', '=', 'pembeli.id')
            ->where('penjualan.status', 'bayar')
            ->whereYear('penjualan.tgl', $year)
            ->selectRaw('pembeli.nama_pembeli, SUM(penjualan_barang.harga_jual * penjualan_barang.jml) as total_penjualan')
            ->groupBy('pembeli.nama_pembeli')
            ->orderByDesc('total_penjualan')
            ->pluck('total_penjualan', 'pembeli.nama_pembeli');

        $jumlahPembeli = $penjualanPerPembeli->count();

        // Buat warna berbeda untuk setiap pembeli
        $defaultColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#66BB6A', '#BA68C8',
            '#F06292', '#81D4FA', '#AED581', '#D4E157',
            '#FF7043', '#90A4AE', '#A1887F', '#E57373'
        ];

        // Jika jumlah pembeli melebihi jumlah warna default, generate warna acak tambahan
        while (count($defaultColors) < $jumlahPembeli) {
            $defaultColors[] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $penjualanPerPembeli->values(),
                    'backgroundColor' => array_slice($defaultColors, 0, $jumlahPembeli),
                ],
            ],
            'labels' => $penjualanPerPembeli->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bisa diganti jadi 'pie' kalau ingin
    }
}
