<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Pengguna</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Daftar Supplier</h2>
    <table>
        <thead>
            <tr>
                <th>Kode Supplier</th>
                <th>Nama Supplier</th>
                <th>Alamat</th>
                <th>Telepon</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list_supplier as $listsupplier)
            <tr>
                <td>{{ $listsupplier->kode_supplier }}</td>
                <td>{{ $listsupplier->nama_supplier }}</td>
                <td>{{ $listsupplier->alamat }}</td>
                <td>{{ $listsupplier->telepon }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>