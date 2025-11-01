<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible"
        content="ie=edge">
    <title>Nota Dinas - {{ $surat->nomor_nota_dinas }}</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            word-wrap: break-word;
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
        {{-- judul surat nota dinas start --}}
        <p
            style="
                    text-align: center;
                    font-size: 14pt;
                    font-weight: bold;
                ">
            NOTA DINAS
        </p>
        {{-- judul surat nota dinas end --}}

        {{-- nomor surat nota dinas start --}}
        <p
            style="
                    text-align: center;
                    font-size: 14pt;
                    padding: -15px 0;
                ">
            NOMOR : {{ $surat->nomor_nota_dinas }}
        </p>
        {{-- nomor surat nota dinas end --}}

        <table
            style="width: 100%; border-collapse: collapse; line-height: 150%; margin-top:40px; text-align: justify;">
            <tr>
                <td style="width: 120px; vertical-align: top">Yth.</td>
                <td style="width: 15px; vertical-align: top">:</td>
                <td style="vertical-align: top">{{ $surat->kepada_yth }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top">Dari</td>
                <td style="vertical-align: top">:</td>
                <td style="vertical-align: top">{{ $surat->dari }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top">Tanggal</td>
                <td style="vertical-align: top">:</td>
                <td style="vertical-align: top">
                    {{ \Carbon\Carbon::parse($surat->tanggal_telaahan)->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top">Perihal</td>
                <td style="vertical-align: top">:</td>
                <td style="vertical-align: top">{{ $surat->perihal_kegiatan }}</td>
            </tr>
        </table>

        <p
            style="
          margin-top:35px;
          text-indent:35px;
          text-align:justify;
          line-height:175%;
          ">
            {{ $surat->dasar_telaahan }}
        </p>

        <p
            style="
          margin-top:20px;
          text-align:justify;
          line-height:175%;
          ">
            Demikian Nota Dinas ini diberikan kepada yang bersangkutan untuk dilaksanakan dengan
            penuh tanggung jawab.
        </p>

    </div>

</body>

</html>
