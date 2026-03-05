<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4a7fb5;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            color: #4a7fb5;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        /* Info */
        .info {
            margin-bottom: 15px;
            font-size: 10px;
        }

        .info span {
            color: #666;
        }

        /* Summary Items */
        .summary {
            margin-bottom: 15px;
        }

        .summary-item {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 10px;
            margin-right: 15px;
        }

        .summary-success {
            background: #d4edda;
            color: #155724;
        }

        .summary-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .summary-info {
            background: #cce5ff;
            color: #004085;
        }

        .summary-warning {
            background: #fff3cd;
            color: #856404;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        thead th {
            background: #4a7fb5;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody td {
            padding: 6px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9px;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        /* Badges */
        .badge {
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-aktif,
        .badge-disetujui,
        .badge-setor,
        .badge-lunas {
            background: #d4edda;
            color: #155724;
        }

        .badge-pasif,
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-keluar,
        .badge-ditolak,
        .badge-tarik,
        .badge-nonaktif {
            background: #f8d7da;
            color: #721c24;
        }

        /* Utilities */
        .text-right {
            text-align: right;
        }

        .page-break {
            page-break-after: always;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 20px;
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
        }

        {{ $css ?? '' }}
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Koperasi Credit Union (CU) Mentari Kasih TP Pomalaa</p>
    </div>

    {{ $slot }}

    <div class="footer">
        <p>Dicetak pada {{ $tanggal }} — Sistem Koperasi Credit Union (CU) Mentari Kasih TP Pomalaa</p>
    </div>
</body>

</html>