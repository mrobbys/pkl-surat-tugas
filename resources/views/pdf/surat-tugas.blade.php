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

                {{-- dasar surat tugas start --}}
        <table style="margin-top: 40px; width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 25%; vertical-align: top; line-height: 1.6;">Dasar</td>
                <td style="width: 20px; vertical-align: top; line-height: 1.6;">:</td>
                <td style="vertical-align: top; text-align: justify; line-height: 1.6;">
                    Persetujuan KEPALA DINAS
                    KOMUNIKASI DAN INFORMATIKA Kota Banjarbaru atas Telaahan Staf Nomor
                    {{ $surat->nomor_telaahan }} tanggal
                    {{ \Carbon\Carbon::parse($surat->tanggal_telaahan)->translatedFormat('j F Y') }},
                    Perihal
                    {{ $surat->perihal_kegiatan }}</td>
            </tr>
        </table>
        {{-- dasar surat tugas end --}}

        {{-- kepada surat tugas start --}}
        @foreach ($surat->pegawaiDitugaskan as $index => $pegawai)
            <table style="width: 100%; margin-top: 15px; margin-bottom: 8px; page-break-inside: avoid;">
                <tr>
                    <td style="width: 25%; vertical-align: top; line-height: 1.5;">
                        @if ($index === 0)
                            Kepada
                        @endif
                    </td>
                    <td style="width: 15px; vertical-align: top; line-height: 1.5;">
                        @if ($index === 0)
                            :
                        @endif
                    </td>
                    <td style="width: 30px; vertical-align: top; line-height: 1.4;">
                        {{ $index + 1 }}.
                    </td>
                    <td style="vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 11pt;">
                            <tr>
                                <td style="width: 25%; padding: 1px 0; line-height: 1.4;">Nama</td>
                                <td style="width: 5%; padding: 1px 0; line-height: 1.4;">:</td>
                                <td style="padding: 1px 0; line-height: 1.4;">
                                    <strong>{{ $pegawai->nama_lengkap }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding: 1px 0; line-height: 1.4;">NIP</td>
                                <td style="padding: 1px 0; line-height: 1.4;">:</td>
                                <td style="padding: 1px 0; line-height: 1.4;">{{ $pegawai->nip }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 1px 0; line-height: 1.4;">Pangkat/Gol</td>
                                <td style="padding: 1px 0; line-height: 1.4;">:</td>
                                <td style="padding: 1px 0; line-height: 1.4;">
                                    {{ $pegawai->pangkatGolongan->pangkat }}
                                    {{ $pegawai->pangkatGolongan->golongan }}/{{ $pegawai->pangkatGolongan->ruang }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 1px 0; line-height: 1.4;">Jabatan</td>
                                <td style="padding: 1px 0; line-height: 1.4;">:</td>
                                <td style="padding: 1px 0; line-height: 1.4;">{{ $pegawai->jabatan }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        @endforeach
        {{-- kepada surat tugas end --}}

        {{-- tanggal pelaksanaan start --}}
        <table style="width: 100%; margin-top: 15px;">
            <tr>
                <td style="width: 25%; vertical-align: top; line-height: 1.5;">Tanggal Pelaksanaan</td>
                <td style="width: 15px; vertical-align: top; line-height: 1.5;">:</td>
                <td style="vertical-align: top; text-align: justify; line-height: 1.5;">
                    @if ($surat->tanggal_mulai === $surat->tanggal_selesai)
                        {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->locale('id')->translatedFormat('j F Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($surat->tanggal_mulai)->locale('id')->translatedFormat('j F Y') }}
                        s/d
                        {{ \Carbon\Carbon::parse($surat->tanggal_selesai)->locale('id')->translatedFormat('j F Y') }}
                    @endif
                </td>
            </tr>
        </table>
        {{-- tanggal pelaksanaan end --}}

        {{-- tempat pelaksanaan start --}}
        <table style="width: 100%; margin-top: 20px;">
            <tr>
                <td style="width: 25%; vertical-align: top; padding-bottom: 20px;">Tempat</td>
                <td style="width: 15px; vertical-align: top; padding-bottom: 20px;">:</td>
                <td style="vertical-align: top; text-align: justify; padding-bottom: 20px;">
                    {{ $surat->tempat_pelaksanaan }}</td>
            </tr>
        </table>
        {{-- tempat pelaksanaan end --}}
    </div>

</body>

</html>
