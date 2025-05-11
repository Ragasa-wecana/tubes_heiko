<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; } /* 👈 Tambahkan ini */
    </style>
</head>
<body>
    <h2>Daftar Presensi</h2>
    <table>
        <thead>
            <tr>
                <th>Id Karyawanr</th>
                <th>Nama Karyawan</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presensi as $p)
            <tr>
               <td>{{ $p->id_karyawan }}</td>
                <td>{{ $p->nama_karyawan }}</td>
                <td>{{ $p->tanggal }}</td>
                <td>{{ $p->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
