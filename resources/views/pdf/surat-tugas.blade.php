<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible"
        content="ie=edge" />
    <title>Surat Tugas - {{ $surat->nomor_surat_tugas }}</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            word-wrap: break-word;
        }

        ol li {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    {{-- header surat start --}}
    <table width="100%"
        style="border-collapse: collapse">
        <tr>
            <td width="5%"
                style="vertical-align: middle">
                <img src="{{ public_path('assets/logo-bjb.png') }}"
                    style="width: 80px; height: auto" />
            </td>

            <td width="80%"
                style="text-align: center; vertical-align: middle">
                <div style="font-size: 16pt; font-weight: bold">PEMERINTAH KOTA BANJARBARU</div>
                <div style="font-size: 20pt; font-weight: bold">
                    DINAS KOMUNIKASI DAN INFORMATIKA
                </div>
                <div style="font-size: 10pt">
                    Alamat Kantor: Jl. Pangeran Suriansyah No.5 Banjarbaru Kalsel
                </div>
                <div style="font-size: 10pt">
                    Telp/Fax. (0511) 6749126
                    <i>Email: kominfobjb@banjarbarukota.go.id</i>
                </div>
            </td>

            <td width="5%"></td>
        </tr>
    </table>
    {{-- header surat end --}}

    {{-- garis batas start --}}
    <div
        style="
                border-top: 2px solid black;
                border-bottom: 4px solid black;
                padding-top: 2px;
                margin-top: 5px;
                margin-bottom: 5px;
            ">
    </div>
    {{-- garis batas end --}}

    <div style="margin-left: 25px; margin-right: 25px; margin-top: 20px;">
        {{-- judul surat tugas start --}}
        <p
            style="
                    text-align: center;
                    font-size: 14pt;
                    font-weight: bold;
                ">
            SURAT TUGAS
        </p>
        {{-- judul surat tugas end --}}

        {{-- nomor surat tugas start --}}
        <p
            style="
                    text-align: center;
                    font-size: 14pt;
                    padding: -15px 0;
                ">
            NOMOR : {{ $surat->nomor_surat_tugas }}
        </p>
        {{-- nomor surat tugas end --}}

        <table style="margin-top: 40px; width: 100%; border-collapse: collapse; line-height: 150%">
            {{-- dasar surat tugas start --}}
            <tr>
                <td style="width: 25%; vertical-align: top; padding-bottom: 20px;">Dasar</td>
                <td style="width: 15px; vertical-align: top; padding-bottom: 20px;">:</td>
                <td style="vertical-align: top; text-align: justify; padding-bottom: 20px;">
                    Persetujuan KEPALA DINAS
                    KOMUNIKASI DAN INFORMATIKA Kota Banjarbaru atas Telaahan Staf Nomor
                    {{ $surat->nomor_telaahan }} tanggal
                    {{ \Carbon\Carbon::parse($surat->tanggal_telaahan)->translatedFormat('d F Y') }},
                    Perihal
                    {{ $surat->perihal_kegiatan }}</td>
            </tr>
            {{-- dasar surat tugas end --}}

            {{-- kepada surat tugas start --}}
            <tr>
                <td style="width: 25%; vertical-align: top; padding-bottom: 20px;">Kepada</td>
                <td style="width: 15px; vertical-align: top; padding-bottom: 20px;">:</td>
                <td style="vertical-align: top; text-align: justify; padding-bottom: 20px;">
                    @foreach ($surat->pegawaiDitugaskan as $index => $pegawai)
                        <table style="width: 100%; margin-bottom: 15px;">
                            <tr>
                                <td style="width: 25px; vertical-align: top;">{{ $index + 1 }}.
                                </td>
                                <td style="vertical-align: top;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="width: 120px; padding: 2px 0;">Nama</td>
                                            <td style="width: 10px; padding: 2px 0;">:</td>
                                            <td style="padding: 2px 0;">
                                                {{ $pegawai->nama_lengkap ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px 0;">Pangkat/Gol</td>
                                            <td style="padding: 2px 0;">:</td>
                                            <td style="padding: 2px 0;">
                                                {{ $pegawai->pangkatGolongan->pangkat }}
                                                {{ $pegawai->pangkatGolongan->golongan }}/{{ $pegawai->pangkatGolongan->ruang }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px 0;">NIP</td>
                                            <td style="padding: 2px 0;">:</td>
                                            <td style="padding: 2px 0;">{{ $pegawai->nip ?? '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px 0;">Jabatan</td>
                                            <td style="padding: 2px 0;">:</td>
                                            <td style="padding: 2px 0;">
                                                {{ $pegawai->jabatan ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    @endforeach
                </td>
            </tr>
            {{-- kepada surat tugas end --}}

            {{-- tanggal pelaksanaan start --}}
            <tr>
                <td style="width: 25%; vertical-align: top; padding-bottom: 20px;">Tanggal
                    Pelaksanaan</td>
                <td style="width: 15px; vertical-align: top; padding-bottom: 20px;">:</td>
                <td style="vertical-align: top; text-align: justify; padding-bottom: 20px;">
                    @if ($surat->tanggal_mulai === $surat->tanggal_selesai)
                        {{-- Jika tanggal sama, tampilkan satu saja --}}
                        {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}
                    @else
                        {{-- Jika tanggal berbeda, tampilkan rentang tanggal --}}
                        {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}
                        s/d
                        {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
            {{-- tanggal pelaksanaan end --}}

            {{-- tempat pelaksanaan start --}}
            <tr>
                <td style="width: 25%; vertical-align: top; padding-bottom: 20px;">Tempat</td>
                <td style="width: 15px; vertical-align: top; padding-bottom: 20px;">:</td>
                <td style="vertical-align: top; text-align: justify; padding-bottom: 20px;">
                    {{ $surat->tempat_pelaksanaan }}</td>
            </tr>
            {{-- tempat pelaksanaan end --}}

        </table>
    </div>

</body>

</html>
