<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $skripsi->judul }}</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }
        .author {
            text-align: center;
            font-style: italic;
        }
        .link {
            text-align: center;
            font-size: 14px;
        }
        .line {
            border-top: 1px solid #000;
            margin: 20px 0;
        }
        .abstract-title {
            font-size: 16px;
            font-weight: bold;
        }
        .content {
            text-align: justify;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="header">
        <p>Universitas Indonesia Library >> Artikel Jurnal</p>
    </div>

    <div class="title">
        {{ $skripsi->judul }}
    </div>

    <div class="author">
        {{ $skripsi->penulis }}, author
    </div>

    <div class="link">
        Deskripsi Lengkap: <a href="{{ $skripsi->link }}">{{ $skripsi->link }}</a>
    </div>

    <div class="line"></div>

    <div class="abstract-title">
        Abstrak
    </div>

    <div class="content">
        <p>{{ $skripsi->abstrak }}</p>
    </div>

    <div class="content">
        <hr>
        {!! $skripsi->abstrak_english !!}
    </div>

</body>
</html>
