<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
        content="ie=edge">
    <title>Rekapitulasi Aktivitas Pegawai</title>
    <style>
        /* Reset & Base */
        * {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }

        /* Kop Surat */
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .kop-surat td {
            vertical-align: middle;
        }

        .kop-title {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .kop-subtitle {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .kop-address {
            font-size: 10pt;
            line-height: 1.3;
        }

        /* Garis Pembatas */
        .divider {
            border-top: 2px solid #000;
            border-bottom: 4px solid #000;
            padding-top: 2px;
            margin: 5px 0 15px 0;
        }

        /* Info Section */
        .info-section {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            margin-bottom: 15px;
        }

        .info-section td {
            vertical-align: top;
            padding: 4px 0;
        }

        .info-label {
            width: 100px;
            font-weight: 500;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
        }

        /* Table Data */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }

        .table-data th {
            background-color: #e8e8e8;
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            vertical-align: middle;
        }

        .table-data tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table-data td {
            vertical-align: middle;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Signature */
        .signature-wrapper {
            width: 100%;
            margin-top: 30px;
        }

        .signature {
            float: right;
            width: 250px;
        }

        .signature p {
            margin: 0;
            line-height: 1.5;
            font-size: 11pt;
        }

        .signature .title {
            margin-top: 5px;
        }

        .signature .nama {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 70px;
        }

        .signature .pangkat {
            font-size: 10pt;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Clear Float */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    {{-- Kop Surat --}}
    <table class="kop-surat">
        <tr>
            <td width="12%"
                style="text-align: center;">
                <img src="{{ public_path('assets/logo-bjb.png') }}"
                    style="width: 90px; height: auto;"
                    alt="Logo" />
            </td>
            <td width="78%"
                style="text-align: center;">
                <div class="kop-title">PEMERINTAH KOTA BANJARBARU</div>
                <div class="kop-subtitle">DINAS KOMUNIKASI DAN INFORMATIKA</div>
                <div class="kop-address">
                    Jl. Pangeran Suriansyah No.5 Banjarbaru, Kalimantan Selatan<br>
                    Telp/Fax. (0511) 6749126 | Email: kominfobjb@banjarbarukota.go.id
                </div>
            </td>
            <td width="10%"></td>
        </tr>
    </table>

    {{-- Garis Pembatas --}}
    <div class="divider"></div>

    {{-- Info Section --}}
    <table class="info-section">
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td class="info-label">Dicetak oleh</td>
                        <td>: {{ $user }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Periode</td>
                        <td>: {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%"
                style="text-align: right;">
                <table style="float: right;">
                    <tr>
                        <td>Tanggal Cetak</td>
                        <td>: {{ $tanggalCetak }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Judul Laporan --}}
    <h1 class="report-title">Rekapitulasi Aktivitas Pegawai</h1>

    {{-- Table Data --}}
    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIP</th>
                <th width="18%">Nama Lengkap</th>
                <th width="20%">Pangkat/Golongan</th>
                <th width="18%">Jabatan</th>
                <th>Jml. Surat Dibuat</th>
                <th>Jml. Ditugaskan</th>
                <th>Total Durasi Perjalanan (Hari)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSuratDibuat = 0;
                $totalDitugaskan = 0;
                $totalDurasi = 0;
            @endphp

            @foreach ($data as $index => $pegawai)
                @php
                    $totalSuratDibuat += $pegawai['jumlah_surat_dibuat'];
                    $totalDitugaskan += $pegawai['jumlah_ditugaskan'];
                    $totalDurasi += $pegawai['total_durasi'];
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pegawai['nip'] }}</td>
                    <td class="text-left">{{ $pegawai['nama_lengkap'] }}</td>
                    <td>{{ $pegawai['pangkat'] }} {{ $pegawai['golongan'] }}</td>
                    <td class="text-left">{{ $pegawai['jabatan'] }}</td>
                    <td>{{ $pegawai['jumlah_surat_dibuat'] }}</td>
                    <td>{{ $pegawai['jumlah_ditugaskan'] }}</td>
                    <td>{{ $pegawai['total_durasi'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Signature --}}
    <div class="signature-wrapper clearfix">
        <div class="signature">
            <p>Banjarbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p class="title">Kepala Dinas,</p>
            <p class="nama">Asep Saputra, S.Kom, M.M</p>
            <p class="pangkat">Pembina Tingkat I</p>
            <p class="pangkat">NIP. 19770909 200604 1 006</p>
        </div>
    </div>

</body>

</html>
