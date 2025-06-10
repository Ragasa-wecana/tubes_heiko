<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Phpml\Clustering\KMeans;
use App\Models\Penjualan;
use App\Models\Pembeli;
use Illuminate\Support\Facades\DB;

class ClusteringVisual extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.clustering-visual';

    public function getViewData(): array
    {
        // Ambil data pembeli dan penjualannya
        $samples = Pembeli::join('penjualan', 'penjualan.pembeli_id', '=', 'pembeli.id')
            ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
            ->where('penjualan.status', 'bayar')
            ->select(
                'pembeli.nama_pembeli as name',
                DB::raw('SUM(penjualan.tagihan) AS x'),
                DB::raw('SUM(penjualan_barang.jml) AS y')
            )
            ->groupBy('pembeli.nama_pembeli')
            ->get()
            ->toArray();

        // Konversi ke koordinat (x, y)
        $coordinates = array_map(function($sample) {
            return [$sample['x'], $sample['y']];
        }, $samples);

        // ❗ Validasi: pastikan koordinat tidak kosong
        if (empty($coordinates)) {
            return [
                'dataPoints' => [],
                'error' => 'Data clustering tidak tersedia. Pastikan ada pembeli yang sudah melakukan pembayaran.',
            ];
        }

        // Jalankan clustering
        $kmeans = new KMeans(3);
        $clusters = $kmeans->cluster($coordinates);

        // Siapkan data untuk ditampilkan
        $dataPoints = [];
        foreach ($clusters as $clusterIndex => $cluster) {
            foreach ($cluster as $point) {
                foreach ($samples as $sample) {
                    if ($sample['x'] == $point[0] && $sample['y'] == $point[1]) {
                        $dataPoints[] = [
                            'x' => $sample['x'],
                            'y' => $sample['y'],
                            'name' => $sample['name'],
                            'cluster' => 'Cluster ' . ($clusterIndex + 1),
                        ];
                        break;
                    }
                }
            }
        }

        return [
            'dataPoints' => $dataPoints,
        ];
    }
}
