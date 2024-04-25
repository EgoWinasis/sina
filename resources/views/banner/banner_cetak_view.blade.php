<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Permintaan Banner {{ $banner->toko }}</title>
    <link rel="stylesheet" href="{{ asset('dist/css/cetak.css') }}">
</head>

<body>
    {{-- <header>
        <h1 style="font-size: 2rem; margin: 2%; text-decoration: underline;">PERMINTAAN BANNER</h1>
    </header> --}}
    <header style="display: flex; justify-content: flex-start; align-items: center;">
        <div>
            <img src="{{ asset('vendor/adminlte/dist/img/logo_nusamba_adiwerna.png') }}" alt="BPR NUSAMBA ADIWERNA Logo"
                style="max-height: 50px;">
        </div>
        <h1 style="font-size: 2rem; margin: 2%; text-decoration: underline;">PERMINTAAN BANNER</h1>
    </header>



    <table class="table-banner">
        <tr>
            <th style="text-align: left">KANTOR</th>
            <td>:</td>
            <td>{{ $banner->kantor }}</td>
        </tr>
        <tr>
            <th style="text-align: left">TOKO</th>
            <td>:</td>
            <td>{{ $banner->toko }}</td>
        </tr>
        <tr>
            <th style="text-align: left">ALAMAT</th>
            <td>:</td>
            <td>{{ $banner->alamat }}</td>
        </tr>
        <tr>
            <th style="text-align: left">DESKRIPSI</th>
            <td>:</td>
            <td>{{ $banner->deskripsi }}</td>
        </tr>
        <tr>
            <th style="text-align: left">UKURAN (cm)</th>
            <td>:</td>
            <td>{{ $banner->panjang }} X {{ $banner->lebar }}</td>
        </tr>
        <tr>
            <th style="text-align: left">HP</th>
            <td>:</td>
            <td>{{ $banner->hp }}</td>
        </tr>
        <tr>
            <th style="text-align: left">TANGGAL PERMINTAAN</th>
            <td>:</td>
            <td>{{ $banner->created_at }}</td>
        </tr>
        <tr>
            <th style="text-align: left">PERMINTAAN OLEH</th>
            <td>:</td>
            <td>{{ $banner->input_by }}</td>
        </tr>
    </table>

    <div class="image-container">
        <h3>DESAIN</h3>
        <img src="{{ asset('/storage/banner/' . $banner->desain) }}" id="banner-image">
    </div>



    @php
        // Parse the date string into a Carbon instance
        $carbonDate = \Carbon\Carbon::parse($banner->created_at);
        // Set the locale to Indonesian
        $carbonDate->setLocale('id');
        // Format the date to display only the date part
        $formattedDate = $carbonDate->isoFormat('D MMMM YYYY');
    @endphp

    {{--  --}}
    {{-- <div style="position: fixed; top: 250mm;"> --}}

        <div style="text-align: center; margin-top: 10px;">
            @if (auth()->user()->kantor == 'Kantor Pusat Operasional')
                <p>Adiwerna,
                    {{ $formattedDate }}
                </p>
            @endif
            @if (auth()->user()->kantor == 'Kantor Cabang Purwokerto')
                <p>Purwokerto,
                    {{ $formattedDate }}
                </p>
            @endif
            @if (auth()->user()->kantor == 'Kantor Cabang Cilacap')
                <p>Cilacap,
                    {{ $formattedDate }}
                </p>
            @endif

            <p style="margin-top: 10px;">Mengetahui,</p>
        </div>
        {{--  --}}
        <table class="table-banner">
            <tr>
                <td style="padding-left: 30px">Staf Marketing Komunitas</td>
                <td></td>
                <td style="text-align: center">Kepala Pusat Operasional</td>
            </tr>
            @if (auth()->user()->kantor == 'Kantor Pusat Operasional')
                <tr>
                    <td style="padding-top: 50px; padding-left: 30px">Hanief Al Fathien</td>
                    <td style="padding-top: 50px;"></td>
                    <td style="padding-top: 50px; text-align: center">Eddy Santoso, A.Md</td>
                </tr>
            @endif
            @if (auth()->user()->kantor == 'Kantor Cabang Purwokerto')
                <tr>
                    <td style="padding-top: 50px; padding-left: 30px">Sari Kurniasih, SE</td>
                    <td style="padding-top: 50px;"></td>
                    <td style="padding-top: 50px; text-align: center">Ainda Desy Luvitasari, SH</td>
                </tr>
            @endif
            @if (auth()->user()->kantor == 'Kantor Cabang Cilacap')
                <tr>
                    <td style="padding-top: 50px; padding-left: 10px">SARI DWI MARETHA, A.Md</td>
                    <td style="padding-top: 50px;"></td>
                    <td style="padding-top: 50px; text-align: center">RETNO DEWI SETYANINGSIH, A.Md
                    </td>
                </tr>
            @endif

        </table>

    {{-- </div> --}}

</body>

</html>
