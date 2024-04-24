<?php

if (!is_null($memo->file_debitur)) {
    $kreditDebitur = json_decode($memo->file_debitur);
}

if (!is_null($memo->file_penjamin)) {
    $kreditPenjamin = json_decode($memo->file_penjamin);
}

$statusDebitur = 'TIDAK MEMILIKI';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MEMO {{ $memo->nama_debitur }}</title>
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
    @if ($memo->tipe_debitur == 'INDIVIDU')
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
                    <td>{{ $memo->tgl_lahir_debitur }}
                    </td>
                    <td>{{ $memo->alamat_debitur }}</td>
                </tr>


            </table>
        </div>
    @else
        <div class="data-debitur">
            <table>
                <tr class="table-header">
                    <td width="30%">Nama Debitur</td>
                    <td width="20%"></td>
                    <td width="50%">No. NPWP</td>
                </tr>
                <tr>
                    <td>{{ $memo->nama_debitur }}</td>
                    <td></td>
                    <td>{{ $memo->nik_debitur }}</td>
                </tr>
                <tr class="table-header">
                    <td>Tempat / Tgl Pendirian</td>
                    <td></td>
                    <td>Alamat</td>
                </tr>
                <tr>
                    <td>{{ $memo->tempat_lahir_debitur }}</td>
                    <td>{{ $memo->tgl_lahir_debitur }}
                    </td>
                    <td>{{ $memo->alamat_debitur }}</td>
                </tr>


            </table>
        </div>
    @endif

    {{-- ringkasan fasilitas --}}
    <div class="kredit-debitur">
        <table class="data-kredit">
            <tr>
                <td width="30%" style="font-weight: bold;">RINGKASAN FASILITAS</td>
                <td width="70%" colspan="2" class="border-bottom">
                    <hr>
                </td>
            </tr>
        </table>

        <table class="table-data">
            <thead>
                <tr>
                    <th width="18%">Plafon Efektif</th>
                    <th width="18%">Baki Debet</th>
                    <th width="15%">Bank Umum</th>
                    <th width="15%">BPR/BPRS</th>
                    <th width="19%">Lembaga Pembiayaan</th>
                    <th width="15%">Lainnya</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($kreditDebitur))

                    @if ($memo->tipe_debitur == 'INDIVIDU')
                        @if (isset($kreditDebitur) && isset($kreditDebitur->individual->ringkasanFasilitas))
                            <tr>
                                <td style="text-align: right">
                                    {{ number_format($kreditDebitur->individual->ringkasanFasilitas->plafonEfektifTotal, 0, ',', '.') }}
                                </td>
                                <td style="text-align: right">
                                    {{ number_format($kreditDebitur->individual->ringkasanFasilitas->bakiDebetTotal, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center">
                                    {{ $kreditDebitur->individual->ringkasanFasilitas->krediturBankUmum }}
                                </td>
                                <td style="text-align: center">
                                    {{ $kreditDebitur->individual->ringkasanFasilitas->{'krediturBPR/S'} }}</td>
                                <td style="text-align: center">
                                    {{ $kreditDebitur->individual->ringkasanFasilitas->krediturLp }}</td>
                                <td style="text-align: center">
                                    {{ $kreditDebitur->individual->ringkasanFasilitas->krediturLainnya }}</td>
                            </tr>
                        @endif
                    @else
                        @if (isset($kreditDebitur) && isset($kreditDebitur->perusahaan->ringkasanFasilitas))
                            <tr>
                                <td style="text-align: right">
                                    {{ number_format($kreditDebitur->perusahaan->ringkasanFasilitas->plafonEfektifTotal, 0, ',', '.') }}
                                </td>
                                <td style="text-align: right">
                                    {{ number_format($kreditDebitur->perusahaan->ringkasanFasilitas->bakiDebetTotal, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center">
                                    {{ $kreditDebitur->perusahaan->ringkasanFasilitas->krediturBankUmum }}
                                </td>
                                <td style="text-align: center">
                                    {{ $kreditDebitur->perusahaan->ringkasanFasilitas->{'krediturBPR/S'} }}</td>
                                <td style="text-align: center">
                                    {{ $kreditDebitur->perusahaan->ringkasanFasilitas->krediturLp }}</td>
                                <td style="text-align: center">
                                    {{ $kreditDebitur->perusahaan->ringkasanFasilitas->krediturLainnya }}</td>
                            </tr>
                        @endif
                    @endif

                @endif
            </tbody>
        </table>
    </div>
    {{-- end fasilitas --}}
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
                @if (isset($kreditDebitur))


                    @php
                        $countDebitur = 0;
                        $statusDebitur = 'TIDAK MEMILIKI';
                    @endphp

                    @if ($memo->tipe_debitur == 'INDIVIDU')
                        @if (isset($kreditDebitur) && count($kreditDebitur->individual->fasilitas->kreditPembiayan) > 0)
                            @foreach ($kreditDebitur->individual->fasilitas->kreditPembiayan as $item)
                                @if ($item->bakiDebet > 0)
                                    @php
                                        $statusDebitur = 'MEMILIKI';
                                        $countDebitur++;
                                        $highestKol = -1;

                                        foreach ($item as $key => $value) {
                                            if (strpos($key, 'Kol') !== false && $value !== '') {
                                                $kol = intval($value);
                                                if ($kol > $highestKol) {
                                                    $highestKol = $kol;
                                                }
                                            }
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{ $item->ljkKet }}</td>
                                        <td style="text-align: right">
                                            {{ number_format($item->plafonAwal, 0, ',', '.') }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ number_format($item->bakiDebet, 0, ',', '.') }}
                                        </td>
                                        <td style="text-align: center">{{ $highestKol == -1 ? 1 : $highestKol }}</td>
                                        @if (!empty($item->agunan))
                                            <td style="text-align: center">{{ $item->agunan[0]->jenisAgunanKet }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        {{-- tgl update --}}
                                        @if ($item->tanggalUpdate == '')
                                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($item->tanggalDibentuk, 0, 8))->locale('id')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                        @else
                                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($item->tanggalUpdate, 0, 8))->locale('id')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @else
                        @if (isset($kreditDebitur) && count($kreditDebitur->perusahaan->fasilitas->kreditPembiayan) > 0)
                            @foreach ($kreditDebitur->perusahaan->fasilitas->kreditPembiayan as $item)
                                @if ($item->bakiDebet > 0)
                                    @php
                                        $statusDebitur = 'MEMILIKI';
                                        $countDebitur++;
                                        $highestKol = -1;

                                        foreach ($item as $key => $value) {
                                            if (strpos($key, 'Kol') !== false && $value !== '') {
                                                $kol = intval($value);
                                                if ($kol > $highestKol) {
                                                    $highestKol = $kol;
                                                }
                                            }
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{ $item->ljkKet }}</td>
                                        <td style="text-align: right">
                                            {{ number_format($item->plafonAwal, 0, ',', '.') }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ number_format($item->bakiDebet, 0, ',', '.') }}
                                        </td>
                                        <td style="text-align: center">{{ $highestKol == -1 ? 1 : $highestKol }}</td>
                                        @if (!empty($item->agunan))
                                            <td style="text-align: center">{{ $item->agunan[0]->jenisAgunanKet }}</td>
                                        @else
                                            <td></td>
                                        @endif

                                        {{-- tgl update --}}
                                        @if ($item->tanggalUpdate == '')
                                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($item->tanggalDibentuk, 0, 8))->locale('id')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                        @else
                                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($item->tanggalUpdate, 0, 8))->locale('id')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif

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

                    {{--  --}}
                @else
                    <tr>
                        <td>NIHIL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                @endif
                <tr style="background-color: rgb(221, 219, 219);">
                    <td colspan="7"></td>
                </tr>
            </tbody>


        </table>


    </div>

    @if ($memo->tipe_penjamin == 'INDIVIDU')
        <div class="data-debitur">
            <table>
                <tr class="table-header">
                    <td width="30%">Nama Penjamin</td>
                    <td width="20%"></td>
                    <td width="50%">No. KTP</td>
                </tr>
                <tr>
                    <td>{{ $memo->nama_penjamin ?? '' }}</td>
                    <td></td>
                    <td>{{ $memo->nik_penjamin ?? '' }}</td>
                </tr>
                <tr class="table-header">
                    <td>Tempat / Tgl Lahir</td>
                    <td></td>
                    <td>Alamat</td>
                </tr>
                <tr>
                    <td>{{ $memo->tempat_lahir_penjamin ?? '' }}</td>
                    @if (!empty($memo->tgl_lahir_penjamin))
                        <td>{{ $memo->tgl_lahir_penjamin }}
                        </td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $memo->alamat_penjamin ?? '' }}</td>
                </tr>


            </table>
        </div>
    @else
        <div class="data-debitur">
            <table>
                <tr class="table-header">
                    <td width="30%">Nama Penjamin</td>
                    <td width="20%"></td>
                    <td width="50%">No. NPWP</td>
                </tr>
                <tr>
                    <td>{{ $memo->nama_penjamin ?? '' }}</td>
                    <td></td>
                    <td>{{ $memo->nik_penjamin ?? '' }}</td>
                </tr>
                <tr class="table-header">
                    <td>Tempat / Tgl Pendirian</td>
                    <td></td>
                    <td>Alamat</td>
                </tr>
                <tr>
                    <td>{{ $memo->tempat_lahir_penjamin ?? '' }}</td>
                    @if (!empty($memo->tgl_lahir_penjamin))
                        <td>{{ $memo->tgl_lahir_penjamin }}
                        </td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $memo->alamat_penjamin ?? '' }}</td>
                </tr>


            </table>
        </div>
    @endif

    {{-- ringkasan fasilitas --}}
    <div class="kredit-debitur">
        <table class="data-kredit">
            <tr>
                <td width="30%" style="font-weight: bold;">RINGKASAN FASILITAS</td>
                <td width="70%" colspan="2" class="border-bottom">
                    <hr>
                </td>
            </tr>
        </table>

        <table class="table-data">
            <thead>
                <tr>
                    <th width="18%">Plafon Efektif</th>
                    <th width="18%">Baki Debet</th>
                    <th width="15%">Bank Umum</th>
                    <th width="15%">BPR/BPRS</th>
                    <th width="19%">Lembaga Pembiayaan</th>
                    <th width="15%">Lainnya</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($kreditPenjamin))

                    @if ($memo->tipe_penjamin == 'INDIVIDU')
                        @if (isset($kreditPenjamin) && isset($kreditPenjamin->individual->ringkasanFasilitas))
                            <tr>
                                <td style="text-align: right">
                                    {{ number_format($kreditPenjamin->individual->ringkasanFasilitas->plafonEfektifTotal, 0, ',', '.') }}
                                </td>
                                <td style="text-align: right">
                                    {{ number_format($kreditPenjamin->individual->ringkasanFasilitas->bakiDebetTotal, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center">
                                    {{ $kreditPenjamin->individual->ringkasanFasilitas->krediturBankUmum }}
                                </td>
                                <td style="text-align: center">
                                    {{ $kreditPenjamin->individual->ringkasanFasilitas->{'krediturBPR/S'} }}</td>
                                <td style="text-align: center">
                                    {{ $kreditPenjamin->individual->ringkasanFasilitas->krediturLp }}</td>
                                <td style="text-align: center">
                                    {{ $kreditPenjamin->individual->ringkasanFasilitas->krediturLainnya }}</td>
                            </tr>
                        @endif
                    @else
                        @if (isset($kreditPenjamin) && isset($kreditPenjamin->perusahaan->ringkasanFasilitas))
                            <tr>
                                <td style="text-align: right">
                                    {{ number_format($kreditPenjamin->perusahaan->ringkasanFasilitas->plafonEfektifTotal, 0, ',', '.') }}
                                </td>
                                <td style="text-align: right">
                                    {{ number_format($kreditPenjamin->perusahaan->ringkasanFasilitas->bakiDebetTotal, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center">
                                    {{ $kreditPenjamin->perusahaan->ringkasanFasilitas->krediturBankUmum }}
                                </td>
                                <td style="text-align: center">
                                    {{ $kreditPenjamin->perusahaan->ringkasanFasilitas->{'krediturBPR/S'} }}</td>
                                <td style="text-align: center">
                                    {{ $kreditPenjamin->perusahaan->ringkasanFasilitas->krediturLp }}</td>
                                <td style="text-align: center">
                                    {{ $kreditPenjamin->perusahaan->ringkasanFasilitas->krediturLainnya }}</td>
                            </tr>
                        @endif
                    @endif

                @endif
            </tbody>
        </table>
    </div>
    {{-- end fasilitas --}}
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

                @if (isset($kreditPenjamin))
                    @php
                        $countPenjamin = 0;

                    @endphp
                    @if ($memo->tipe_penjamin == 'INDIVIDU')
                        @if (isset($kreditPenjamin) && count($kreditPenjamin->individual->fasilitas->kreditPembiayan) > 0)
                            @foreach ($kreditPenjamin->individual->fasilitas->kreditPembiayan as $itemPenjamin)
                                @if ($itemPenjamin->bakiDebet > 0)
                                    @php
                                        $countPenjamin++;
                                        $highestKol = -1;

                                        foreach ($itemPenjamin as $key => $value) {
                                            if (strpos($key, 'Kol') !== false && $value !== '') {
                                                $kol = intval($value);
                                                if ($kol > $highestKol) {
                                                    $highestKol = $kol;
                                                }
                                            }
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{ $itemPenjamin->ljkKet }}</td>
                                        <td style="text-align: right">
                                            {{ number_format($itemPenjamin->plafonAwal, 0, ',', '.') }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ number_format($itemPenjamin->bakiDebet, 0, ',', '.') }}</td>
                                        <td style="text-align: center">{{ $highestKol == -1 ? 1 : $highestKol }}</td>
                                        @if (!empty($itemPenjamin->agunan))
                                            <td style="text-align: center">
                                                {{ $itemPenjamin->agunan[0]->jenisAgunanKet }}
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if ($itemPenjamin->tanggalUpdate == '')
                                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($itemPenjamin->tanggalDibentuk, 0, 8))->locale('id')->isoFormat('D MMMM YYYY') }}
                                            </td>
                                        @else
                                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($itemPenjamin->tanggalUpdate, 0, 8))->locale('id')->isoFormat('D MMMM YYYY') }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @else
                        @if (isset($kreditPenjamin) && count($kreditPenjamin->perusahaan->fasilitas->kreditPembiayan) > 0)
                            @foreach ($kreditPenjamin->perusahaan->fasilitas->kreditPembiayan as $itemPenjamin)
                                @if ($itemPenjamin->bakiDebet > 0)
                                    @php
                                        $countPenjamin++;
                                        $highestKol = -1;

                                        foreach ($itemPenjamin as $key => $value) {
                                            if (strpos($key, 'Kol') !== false && $value !== '') {
                                                $kol = intval($value);
                                                if ($kol > $highestKol) {
                                                    $highestKol = $kol;
                                                }
                                            }
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{ $itemPenjamin->ljkKet }}</td>
                                        <td style="text-align: right">
                                            {{ number_format($itemPenjamin->plafonAwal, 0, ',', '.') }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ number_format($itemPenjamin->bakiDebet, 0, ',', '.') }}</td>
                                        <td style="text-align: center">{{ $highestKol == -1 ? 1 : $highestKol }}</td>
                                        @if (!empty($itemPenjamin->agunan))
                                            <td style="text-align: center">
                                                {{ $itemPenjamin->agunan[0]->jenisAgunanKet }}
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if ($itemPenjamin->tanggalUpdate == '')
                                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($itemPenjamin->tanggalDibentuk, 0, 8))->locale('id')->isoFormat('D MMMM YYYY') }}
                                            </td>
                                        @else
                                            <td> {{ \Carbon\Carbon::createFromFormat('Ymd', substr($itemPenjamin->tanggalUpdate, 0, 8))->locale('id')->isoFormat('D MMMM YYYY') }}
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                    
                    {{-- cek jika nihil --}}
                    @if ($countPenjamin == 0 && $memo->nama_penjamin != null)
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
                    @if ($countPenjamin == 0 || $countPenjamin < 5)
                        @while ($countPenjamin < 5)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                $countPenjamin++;
                            @endphp
                        @endwhile
                    @endif
                @else
                    <tr>
                        <td>NIHIL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @for ($i = 0; $i < 2; $i++)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
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
                    SETELAH DILAKUKAN PENCARIAN INFORMASI DEBITUR MELALUI SLIK, DEBITUR <b>{{ $statusDebitur }}</b>
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
        <table>
            <tr>
                <td width="40" style="padding-left: 30px">KABID OPERASIONAL</td>
                <td width="20"></td>
                <td width="40" style="text-align: center">PETUGAS SLIK</td>
            </tr>
            @if (auth()->user()->kantor == 'Kantor Pusat Operasional')
                <tr>
                    <td width="40" style="padding-top: 80px; padding-left: 10px">ADITYA GALANG MAHAFI, S.Kom</td>
                    <td width="20" style="padding-top: 80px;"></td>
                    <td width="40" style="padding-top: 80px; text-align: center">JUWIETA MEYDHIA, S.Kom</td>
                </tr>
            @endif
            @if (auth()->user()->kantor == 'Kantor Cabang Purwokerto')
                <tr>
                    <td width="40" style="padding-top: 80px; padding-left: 30px">Sari Kurniasih, SE</td>
                    <td width="20" style="padding-top: 80px;"></td>
                    <td width="40" style="padding-top: 80px; text-align: center">Ainda Desy Luvitasari, SH</td>
                </tr>
            @endif
            @if (auth()->user()->kantor == 'Kantor Cabang Cilacap')
                <tr>
                    <td width="40" style="padding-top: 80px; padding-left: 10px">SARI DWI MARETHA, A.Md</td>
                    <td width="20" style="padding-top: 80px;"></td>
                    <td width="40" style="padding-top: 80px; text-align: center">RETNO DEWI SETYANINGSIH, A.Md
                    </td>
                </tr>
            @endif

        </table>

    </div>
</body>

</html>
