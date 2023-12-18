<?php

// Check the encoding
if (!is_null($memo->file_debitur)) {
    $encodingDebitur = mb_detect_encoding($memo->file_debitur, 'UTF-8', true);
}
if (!is_null($memo->file_penjamin)) {
    $encodingPenjamin = mb_detect_encoding($memo->file_penjamin, 'UTF-8', true);
}

if ($encodingDebitur != 'UTF-8') {
    $jsonStringDebitur = mb_convert_encoding($memo->file_debitur, 'UTF-8');
}
if ($encodingPenjamin != 'UTF-8') {
    $jsonStringPenjamin = mb_convert_encoding($memo->file_penjamin, 'UTF-8');
}

// Now decode the JSON
$dataDebitur = json_decode($jsonStringDebitur);
$dataPenjamin = json_decode($jsonStringPenjamin);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MEMO {{$memo->nama_debitur}}</title>
    <link rel="stylesheet" href="{{ asset('dist/css/cetak.css') }}">
</head>

<body>
    <header>
        <p>MEMO ANALISA</p>
        <p>INFORMASI DEBITUR (IDeb)</p>
        <p>Sistem Layanan Informasi Keuangan (SLIK)</p>
    </header>
    <main>
        <p class="memo-intern">MEMO INTERN</p>
    </main>

    <div class="data-debitur">
        <table>
            <tr class="table-header">
                <td width="30%">Nama Debitur</td>
                <td width="20%"></td>
                <td width="50%">No. KTP</td>
            </tr>
            <tr>
                <td>{{ $memo->nama_debitur }}</td>
                <td></td>
                <td>{{ $memo->nik_debitur }}</td>
            </tr>
            <tr class="table-header">
                <td>Tempat / Tgl Lahir</td>
                <td></td>
                <td>Alamat</td>
            </tr>
            <tr>
                <td>{{ $memo->tempat_lahir_debitur }}</td>
                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $memo->tgl_lahir_debitur)->locale('id')->isoFormat('D-MM-YYYY')  }}</td>
                <td>{{ $memo->alamat_debitur }}</td>
            </tr>


        </table>
    </div>

    <div class="kredit-debitur">
        <table class="data-kredit">
            <tr>
                <td width="30%" style="font-weight: bold;">DATA KREDIT</td>
                <td width="70%" colspan="2" class="border-bottom">
                    <hr>
                </td>
            </tr>
        </table>

        <table class="table-data">
            <thead>
                <tr>
                    <th width="35%">Nama Bank</th>
                    <th width="15%">Plafon</th>
                    <th width="15%">Baki Debet</th>
                    <th width="5%">Kol</th>
                    <th width="15%">Jaminan</th>
                    <th width="15%">Tgl Update</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $countDebitur = 0;
                @endphp
                @foreach ($dataDebitur->individual->fasilitas->kreditPembiayan as $item)
                    @if ($item->bakiDebet > 500000)
                        @php
                            $countDebitur++;
                        @endphp
                        <tr>
                            <td>{{ $item->ljkKet }}</td>
                            <td>{{ number_format($item->plafon, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->bakiDebet, 0, ',', '.') }}</td>
                            <td>{{ $item->kualitas }}</td>
                            @if (!empty($item->agunan))
                                <td>{{ $item->agunan[0]->jenisAgunanKet }}</td>
                            @else
                                <td></td>
                            @endif
                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($item->tanggalUpdate, 0, 8))->locale('id')->isoFormat('DD MMMM YYYY') }}
                            </td>
                        </tr>
                    @endif
                @endforeach
                {{-- cek jika nihil --}}
                @if ($countDebitur == 0)
                    <tr>
                        <td>NIHIL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                {{--  --}}
                @if ($countDebitur == 0 || $countDebitur < 5)
                    @while ($countDebitur < 5)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            $countDebitur++;
                        @endphp
                    @endwhile
                @endif
                <tr style="background-color: rgb(221, 219, 219);">
                    <td colspan="6"></td>

                </tr>
            </tbody>


        </table>
    </div>
    <div class="data-debitur">
        <table>
            <tr class="table-header">
                <td width="30%">Nama Penjamin</td>
                <td width="20%"></td>
                <td width="50%">No. KTP</td>
            </tr>
            <tr>
                <td>{{ $memo->nama_penjamin }}</td>
                <td></td>
                <td>{{ $memo->nik_penjamin }}</td>
            </tr>
            <tr class="table-header">
                <td>Tempat / Tgl Lahir</td>
                <td></td>
                <td>Alamat</td>
            </tr>
            <tr>
                <td>{{ $memo->tempat_lahir_penjamin }}</td>
                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $memo->tgl_lahir_penjamin)->locale('id')->isoFormat('DD-MM-YYYY')  }}</td>
                <td>{{ $memo->alamat_penjamin }}</td>
            </tr>


        </table>
    </div>

    <div class="kredit-debitur">
        <table class="data-kredit">
            <tr>
                <td width="30%" style="font-weight: bold;">DATA KREDIT</td>
                <td width="70%" colspan="2" class="border-bottom">
                    <hr>
                </td>
            </tr>
        </table>

        <table class="table-data">
            <thead>
                <tr>
                    <th width="35%">Nama Bank</th>
                    <th width="15%">Plafon</th>
                    <th width="15%">Baki Debet</th>
                    <th width="5%">Kol</th>
                    <th width="15%">Jaminan</th>
                    <th width="15%">Tgl Update</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $countDebitur = 0;
                @endphp
                @foreach ($dataDebitur->individual->fasilitas->kreditPembiayan as $item)
                    @if ($item->bakiDebet > 500000)
                        @php
                            $countDebitur++;
                        @endphp
                        <tr>
                            <td>{{ $item->ljkKet }}</td>
                            <td>{{ number_format($item->plafon, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->bakiDebet, 0, ',', '.') }}</td>
                            <td>{{ $item->kualitas }}</td>
                            @if (!empty($item->agunan))
                                <td>{{ $item->agunan[0]->jenisAgunanKet }}</td>
                            @else
                                <td></td>
                            @endif
                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($item->tanggalUpdate, 0, 8))->locale('id')->isoFormat('D MMMM YYYY') }}
                            </td>
                        </tr>
                    @endif
                @endforeach
                {{-- cek jika nihil --}}
                @if ($countDebitur == 0)
                    <tr>
                        <td>NIHIL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                {{--  --}}
                @if ($countDebitur == 0 || $countDebitur < 5)
                    @while ($countDebitur < 5)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            $countDebitur++;
                        @endphp
                    @endwhile
                @endif
                <tr style="background-color: rgb(221, 219, 219);">
                    <td colspan="6"></td>

                </tr>
            </tbody>


        </table>

        <table class="data-kredit">
            <tr>
                <td width="30%" style="font-weight: bold;">KETERANGAN</td>
                <td width="70%" colspan="2" class="border-bottom">
                    <hr>
                </td>
            </tr>
            <tr>
                <td colspan="2" width="100%">
                    SETELAH DILAKUKAN PENCARIAN INFORMASI DEBITUR MELALUI SLIK, DEBITUR <b>TIDAK MEMILIKI / MEMILIKI</b>
                    *)
                    KREDIT DI BANK UMUM/BPR/BPRS/LEMBAGA
                </td>
            </tr>
        </table>

        @php
            // Parse the date string into a Carbon instance
            $carbonDate = \Carbon\Carbon::parse($memo->created_at);
            // Set the locale to Indonesian
            $carbonDate->setLocale('id');
            // Format the date to display only the date part
            $formattedDate = $carbonDate->isoFormat('D MMMM YYYY');
        @endphp

        {{--  --}}
        <div style="text-align: center; margin-top: 10px;">
            <p>Adiwerna,
                {{ $formattedDate }}
            </p>
            <p style="margin-top: 10px;">Mengetahui,</p>
        </div>

        {{--  --}}
        <table>
            <tr>
                <td width="40" style="padding-left: 30px">KABID OPERASIONAL</td>
                <td width="20"></td>
                <td width="40" style="text-align: center">PETUGAS SLIK</td>
            </tr>
            <tr>
                <td width="40" style="padding-top: 80px; padding-left: 10px">ADITYA GALANG MAHAFI, S.Kom</td>
                <td width="20" style="padding-top: 80px;"></td>
                <td width="40" style="padding-top: 80px; text-align: center">JUWIETA MEYDHIA, S.Kom</td>
            </tr>
        </table>
        <div style="padding-top: 20px;">
            <span>*) Coret Salah Satu</span>
        </div>
    </div>
</body>

</html>
