<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible"
        content="ie=edge" />
    <title>Telaah Staf - {{ $surat->nomor_telaahan }}</title>
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

    <div style="margin-left: 25px; margin-right: 25px">
        {{-- judul surat telaahan staf start --}}
        <p
            style="
                    text-align: center;
                    font-size: 14pt;
                    font-weight: bold;
                    text-decoration: underline;
                ">
            TELAAHAN STAF
        </p>
        {{-- judul surat telaahan staf end --}}

        {{-- detail surat telaah staf start --}}
        <table
            style="width: 100%; border-collapse: collapse; line-height: 1.5; text-align: justify;">
            <tr>
                <td style="width: 120px; vertical-align: top">Kepada Yth</td>
                <td style="width: 15px; vertical-align: top">:</td>
                <td style="vertical-align: top">{{ $surat->kepada_yth }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top">Dari</td>
                <td style="vertical-align: top">:</td>
                <td style="vertical-align: top">{{ $surat->dari }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top">Nomor</td>
                <td style="vertical-align: top">:</td>
                <td style="vertical-align: top">{{ $surat->nomor_telaahan }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top">Tanggal</td>
                <td style="vertical-align: top">:</td>
                <td style="vertical-align: top">
                    {{ \Carbon\Carbon::parse($surat->tanggal_telaahan)->translatedFormat('j F Y') }}
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top">Perihal</td>
                <td style="vertical-align: top">:</td>
                <td style="vertical-align: top">{{ $surat->perihal_kegiatan }}</td>
            </tr>
        </table>
        {{-- detail surat telaah staf end --}}

        {{-- batas surat start --}}
        <hr
            style="
                    height: 2px;
                    color: black;
                    border: none;
                    width: 100%;
                    text-align: center;
                " />
        {{-- batas surat end --}}

        {{-- dasar surat start --}}
        <p style="font-weight: bold">I.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DASAR&nbsp; :</p>
        <div style="text-align: justify; padding: -10px 0; text-indent: 35px; line-height: 175%">
            <p>
                {{ $surat->dasar_telaahan }}
            </p>
        </div>
        {{-- dasar surat end --}}

        {{-- isi surat start --}}
        <p style="font-weight: bold">II.&nbsp;&nbsp;&nbsp;&nbsp;ISI&nbsp; :</p>
        <div style="margin-left: 35px; text-align: justify; padding: -15px 0">
            <p>
                Berdasarkan perihal tersebut, dengan ini disampaikan hal-hal sebagai berikut :
            </p>
        </div>
        <div style="margin-left: 15px; text-align: justify; padding: 5px 0 15px">
            {!! $surat->isi_telaahan !!}
        </div>
        {{-- isi surat end --}}

        {{-- saran surat start --}}
        <p style="font-weight: bold">III.&nbsp;&nbsp;&nbsp;&nbsp;SARAN&nbsp; :</p>
        <div style="margin-left: 40px; text-align: justify; padding: -15px 0 5px">
            <p>Pelaksanaan Perjalanan Dinas, rencana akan diikuti oleh :</p>
        </div>
        <div style="margin-left: 40px; text-align: justify; padding: -10px 0">
            @foreach ($surat->pegawaiDitugaskan as $pegawai)
                <table style="width: 100%; font-size: 12pt; margin-bottom: 20px">
                    <tr>
                        <td style="width: 25%">• Nama</td>
                        <td style="width: 5%">:</td>
                        <td><strong>{{ $pegawai->nama_lengkap }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px">NIP</td>
                        <td>:</td>
                        <td>{{ $pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px">Pangkat/Gol</td>
                        <td>:</td>
                        <td>
                            {{ $pegawai->pangkatGolongan->pangkat }}
                            {{ $pegawai->pangkatGolongan->golongan }}/{{ $pegawai->pangkatGolongan->ruang }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px">Jabatan</td>
                        <td>:</td>
                        <td>{{ $pegawai->jabatan }}</td>
                    </tr>
                </table>
            @endforeach
        </div>
        {{-- saran surat end --}}

        <div style="margin-left: 40px; text-align: justify; padding: 15px 5px">
            <p>Demikian disampaikan mohon putusan dan arahan selanjutnya.</p>
        </div>
    </div>
</body>

</html>
