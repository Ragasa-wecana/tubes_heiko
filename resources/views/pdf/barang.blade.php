<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Stock Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; } /* 👈 Tambahkan ini */
    </style>
</head>
<body>
    <h2>Daftar Stock Barang</h2>
    <table>
        <thead>
            <tr>
                <th>Kode Barangr</th>
                <th>Nama Barang</th>
                <th>Kategori Barang</th>
                <th>Ukuran</th>
                <th>Harga</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $p)
            <tr>
                <td>{{ $p->kode_barang }}</td>
               <td>{{ $p->nama_barang }}</td>
                <td>{{ $p->kategori_barang }}</td>
                <td>{{ $p->ukuran }}</td>
                <td class="text-right">{{ rupiah($p->harga_barang) }}</td>
                <td>{{ $p->stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>