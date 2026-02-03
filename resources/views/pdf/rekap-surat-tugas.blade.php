<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
        content="ie=edge">
    <title>Rekapitulasi Surat Tugas</title>
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
    <h1 class="report-title">Rekapitulasi Surat Tugas</h1>

    {{-- Table Data --}}
    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal Surat</th>
                <th width="12%">Nomor Surat Tugas</th>
                <th width="25%">Perihal</th>
                <th width="18%">Tempat Pelaksanaan</th>
                <th width="10%">Lama Perjalanan</th>
                <th width="24%">Nama Pegawai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                @php
                    // menghitung lama perjalanan
                    $lamaPerjalanan =
                        \Carbon\Carbon::parse($item->tanggal_mulai)->diffInDays(
                            \Carbon\Carbon::parse($item->tanggal_selesai),
                        ) + 1;

                    // format nama pegawai
                    $pegawaiList = $item->pegawaiDitugaskan;
                    $totalPegawai = $pegawaiList->count();

                    if ($totalPegawai === 0) {
                        $namaPegawai = '-';
                    } elseif ($totalPegawai <= 2) {
                        // tampilkan semua nama jika <= 2 orang
                        $namaPegawai = $pegawaiList->pluck('nama_lengkap')->join(', ');
                    } else {
                        // tampilkan 2 nama pertama + "dan X lainnya"
                        $duaNamaPertama = $pegawaiList->take(2)->pluck('nama_lengkap')->join(', ');
                        $sisanya = $totalPegawai - 2;
                        $namaPegawai = $duaNamaPertama . ', dan ' . $sisanya . ' lainnya';
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($item->tanggal_status_dua)->translatedFormat('d M Y') }}
                    </td>
                    <td>{{ $item->nomor_surat_tugas }}</td>
                    <td class="text-left">{{ Str::limit($item->perihal_kegiatan, 60) }}</td>
                    <td class="text-left">{{ $item->tempat_pelaksanaan }}</td>
                    <td>{{ $lamaPerjalanan }} hari</td>
                    <td class="text-left">
                        {{ $namaPegawai }}
                        @if ($totalPegawai > 2)
                            <br>
                            <small style="color: #939185; font-size: 8pt;">
                                (Total: {{ $totalPegawai }} pegawai)
                            </small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7"
                        style="padding: 20px; font-style: italic; color: #939185;">
                        Tidak ada data surat tugas pada periode yang dipilih.
                    </td>
                </tr>
            @endforelse
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
