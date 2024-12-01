<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metadata PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .content {
            width: 100%;
            margin: 0 auto;
        }
        .section {
            margin-bottom: 15px;
        }
        .section strong {
            display: inline-block;
            width: 150px; /* Untuk menyamakan posisi kolom */
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        ul li {
            margin-bottom: 5px;
        }
        ul li a {
            color: #007bff;
            text-decoration: none;
        }
        ul li a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Universitas Suryakancana Library</h1>

    <div class="judul">
        <div class="isi">
            <strong>Judul:</strong>
        </div>
        <div class="div">
            <p>{{ $skripsi->judul }}</p>
        </div>
        <div class="judul">
            <strong>Pengarang:</strong>
            {{ $skripsi->penulis }}
        </div>
        <div class="judul">
            <strong>Penerbitan:</strong>
            {{ $skripsi->prodi }} Universitas Suryakancana
        </div>
        <div class="judul">
            <strong>Link Terkait:</strong>
            <ul>
                <li><a href="#">Dokumen Yang Mirip</a></li>
                <li><a href="/home/skripsi">Universitas Suryakancana Library</a></li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Universitas Suryakancana Library. All Rights Reserved.</p>
    </div>
</body>
</html>
