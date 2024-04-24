@php
    use Carbon\Carbon;
    // get this month
    $allowedAo = ['BR', 'TN', 'MS', 'MA', 'LG', 'RB', 'AY', 'TE', 'BN', 'RK', 'DT', 'ML'];
    $allowedAoRecords = \App\Models\Models\LoanModel::whereIn('L0ACOF', $allowedAo)
        ->where('L0COLS', '<>', 'E')
        ->where('L0STAT', 'A')
        ->where('OUTS', '>', 0)
        ->groupBy('L0ACOF')
        ->selectRaw('L0ACOF as ao, COUNT(OUTS) as nsb, SUM(OUTS)/1000 as plafon')
        ->get();
    // Retrieve records for not allowedAo
    $notAllowedAoRecords = \App\Models\Models\LoanModel::whereNotIn('L0ACOF', $allowedAo)
        ->where('L0COLS', '<>', 'E')
        ->where('L0STAT', 'A')
        ->where('OUTS', '>', 0)
        ->groupBy('L0ACOF')
        ->selectRaw('L0ACOF as ao, COUNT(OUTS) as nsb, SUM(OUTS)/1000 as plafon')
        ->get();

    $colsAllowed = \App\Models\Models\LoanModel::whereIn('L0ACOF', $allowedAo)
        ->where('L0COLS', '<>', 'E')
        ->whereRaw('CAST(L0COLS AS UNSIGNED) > 2') // Cast L0COLS to UNSIGNED numeric type
        ->where('L0STAT', 'A')
        ->where('OUTS', '>', 0)
        ->groupBy('L0ACOF')
        ->selectRaw('L0ACOF as ao, SUM(OUTS)/1000 as plafon')
        ->get();

    $colsNotAllowed = \App\Models\Models\LoanModel::whereNotIn('L0ACOF', $allowedAo)
        ->where('L0COLS', '<>', 'E')
        ->whereRaw('CAST(L0COLS AS UNSIGNED) > 2') // Cast L0COLS to UNSIGNED numeric type
        ->where('L0STAT', 'A')
        ->where('OUTS', '>', 0)
        ->groupBy('L0ACOF')
        ->selectRaw('L0ACOF as ao, SUM(OUTS)/1000 as plafon')
        ->get();

    $allowedAoRecords = $allowedAoRecords->map(function ($item) {
        $item->nsb = (int) $item->nsb;
        $item->plafon = (float) $item->plafon;
        return $item;
    });

    $notAllowedAoRecords = $notAllowedAoRecords->map(function ($item) {
        $item->nsb = (int) $item->nsb;
        $item->plafon = (float) $item->plafon;
        return $item;
    });
    // kol
    $kolRecords = $colsAllowed->map(function ($item) {
        $item->plafon = (int) $item->plafon;
        return $item;
    });
    $kolNotRecords = $colsNotAllowed->map(function ($item) {
        $item->plafon = (int) $item->plafon;
        return $item;
    });

    // Merge the results
    $loans = collect();

    // Append allowedAoRecords to the $loans collection
    $loans = $loans->merge($allowedAoRecords);

    // Append notAllowedAoRecords to the $loans collection
    $loans->push(...$notAllowedAoRecords);

    $arrayKyd = [];

    foreach ($loans as $loan) {
        $ao = $loan->ao;

        if (in_array($ao, $allowedAo)) {
            // AO is in the allowed AO list
            $arrayKyd[$ao] = [
                'nsb' => $loan->nsb,
                'plafon' => $loan->plafon,
            ];
        } else {
            // AO is not in the allowed AO list
            if (isset($arrayKyd['dll'])) {
                // 'dll' already exists, accumulate values
                $arrayKyd['dll']['nsb'] += $loan->nsb;
                $arrayKyd['dll']['plafon'] += $loan->plafon;
            } else {
                // 'dll' does not exist, create a new entry
                $arrayKyd['dll'] = [
                    'nsb' => $loan->nsb,
                    'plafon' => $loan->plafon,
                ];
            }
        }
    }

    // Merge the results
    $cols = collect();

    // Append allowedAoRecords to the $cols collection
    $cols = $cols->merge($colsAllowed);

    // Append notAllowedAoRecords to the $cols collection
    $cols->push(...$colsNotAllowed);

    $arrayCols = [];
    foreach ($allowedAo as $ao) {
        $arrayCols[$ao] = ['sumcols' => 0];
    }
    $arrayCols['dll'] = ['sumcols' => 0];
    foreach ($cols as $col) {
        $ao = $col->ao;

        // Check if the AO is in the allowed AO list
        if (in_array($ao, $allowedAo)) {
            // AO is in the allowed AO list, update the sumcols value
            if (!isset($arrayCols[$ao])) {
                $arrayCols[$ao] = ['sumcols' => 0];
            }
            // Accumulate the sumcols value
            $arrayCols[$ao]['sumcols'] += $col->plafon;
        } else {
            // AO is not in the allowed AO list, update the sumcols value for 'dll'
            $arrayCols['dll']['sumcols'] += $col->plafon;
        }
    }

    $sumTemp = 0;
    foreach ($arrayKyd as $item) {
        $sumTemp += $item['plafon'];
    }

    // get kyd before month

    // Get the current month
    $currentMonth = Carbon::now()->month;
    // Calculate the month before the current month
    $previousMonth = $currentMonth - 1 <= 0 ? 12 : $currentMonth - 1;
    $kydBefore = \App\Models\ModelPencapaian::where('bulan', $previousMonth)->select('kode_ao', 'nasabah', 'os')->get();

    $arrayKydBefore = [];

    foreach ($kydBefore as $item) {
        $arrayKydBefore[$item->kode_ao] = [
            'nasabah' => $item->nasabah,
            'os' => $item->os,
        ];
    }
@endphp



<div class="row">
    <div class="col-xl-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <div class="col-md-10">
                    <span>
                        <i class="fas fa-table mr-1"></i>Table Jumlah Droping -
                        {{ now()->locale('id_ID')->isoFormat('D MMMM Y') }}
                    </span>
                </div>
                @if (Auth::user()->role == 'super')
                    <div class="col-md-2 text-right">
                        <i title="Set Pencapaian Bulan Lalu" class="fas fa-plus" style="cursor: pointer"
                            id="modalPencapaian"></i>
                    </div>
                @endif

            </div>



            <div class="card-body table-responsive">
                <table class="table table-striped table-hover table-borderless" id="table-droping">
                    <thead>
                        <tr>
                            <th class="table-success"></th>
                            <th class="table-success"></th>
                            <th colspan="2" class="table-info text-center">
                                {{ now()->subMonth()->locale('id_ID')->isoFormat('MMMM YYYY') }}</th>
                            <th colspan="2" class="table-warning text-center">
                                {{ now()->locale('id_ID')->isoFormat('MMMM YYYY') }}</th>
                            <th class="table-primary"></th>
                            <th class="table-danger"></th>
                        </tr>
                        <tr>
                            <th class="table-success text-center">AO</th>
                            <th class="table-success text-center">Nama</th>
                            <th class="table-info text-center">NSB</th>
                            <th class="table-info text-center">OS</th>
                            <th class="table-warning text-center">NSB</th>
                            <th class="table-warning text-center">OS</th>
                            <th class="table-primary text-center">Tumbuh</th>
                            <th class="table-danger text-center">% NPL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalKYD = 0;
                            $totalKYDLalu = 0;
                            $totalNasabah = 0;
                            $totalNasabahLalu = 0;
                            $totalPersen = 0;
                        @endphp
                        @foreach ($results as $item)
                            <tr>
                                <td class="text-center">{{ $item['kode_ao'] }}</td>
                                <td class="text-left">{{ Illuminate\Support\Str::upper($item['nama_ao']) }}</td>
                                <td class="text-center">
                                    {{ isset($arrayKydBefore[$item['kode_ao']]['nasabah']) ? $arrayKydBefore[$item['kode_ao']]['nasabah'] : 0 }}
                                </td>
                                <td class="text-right">
                                    {{ number_format(isset($arrayKydBefore[$item['kode_ao']]['os']) ? $arrayKydBefore[$item['kode_ao']]['os'] : 0, 0, ',', '.') }}
                                </td>
                                <td class="text-center">{{ $arrayKyd[$item['kode_ao']]['nsb'] }}</td>
                                <td class="text-right">
                                    {{ number_format($arrayKyd[$item['kode_ao']]['plafon'], 0, ',', '.') }}</td>

                                <td class="text-right ">
                                    {{ number_format($arrayKyd[$item['kode_ao']]['plafon'] - (isset($arrayKydBefore[$item['kode_ao']]['os']) ? $arrayKydBefore[$item['kode_ao']]['os'] : 0), 0, ',', '.') }}
                                </td>
                                @php
                                    $sumcols = $arrayCols[$item['kode_ao']]['sumcols'];
                                    $percentage = ($sumcols / $sumTemp) * 100;
                                @endphp
                                <td class="text-right">
                                    {{ number_format($percentage, 2) }}%
                                </td>
                            </tr>

                            @php
                                $totalKYD += $arrayKyd[$item['kode_ao']]['plafon'];
                                $totalKYDLalu += isset($arrayKydBefore[$item['kode_ao']]['os'])
                                    ? $arrayKydBefore[$item['kode_ao']]['os']
                                    : 0;
                                $totalNasabah += $arrayKyd[$item['kode_ao']]['nsb'];
                                $totalNasabahLalu += isset($arrayKydBefore[$item['kode_ao']]['nasabah'])
                                    ? $arrayKydBefore[$item['kode_ao']]['nasabah']
                                    : 0;
                                $totalPersen += $percentage;
                            @endphp
                        @endforeach

                        <tr>
                            <td class="text-center">N/A</td>
                            <td class="text-left"></td>
                            <td class="text-center">
                                {{ isset($arrayKydBefore['DLL']['nasabah']) ? $arrayKydBefore['DLL']['nasabah'] : 0 }}
                            </td>
                            <td class="text-right">
                                {{ number_format(isset($arrayKydBefore['DLL']['os']) ? $arrayKydBefore['DLL']['os'] : 0, 0, ',', '.') }}
                            </td>
                            <td class="text-center">{{ $arrayKyd['dll']['nsb'] }}</td>
                            <td class="text-right">
                                {{ number_format($arrayKyd['dll']['plafon'], 0, ',', '.') }}</td>
                            <td class="text-right ">
                                {{ number_format($arrayKyd['dll']['plafon'] - (isset($arrayKydBefore['DLL']['os']) ? $arrayKydBefore['DLL']['os'] : 0), 0, ',', '.') }}
                            </td>
                            @php
                                $sumcols = $arrayCols['dll']['sumcols'];
                                $percentage = ($sumcols / $sumTemp) * 100;
                            @endphp
                            <td class="text-right">
                                {{ number_format($percentage, 2) }}%
                            </td>
                        </tr>

                        @php
                            $totalKYD += $arrayKyd['dll']['plafon'];
                            $totalKYDLalu += isset($arrayKydBefore['DLL']['os']) ? $arrayKydBefore['DLL']['os'] : 0;
                            $totalNasabah += $arrayKyd['dll']['nsb'];
                            $totalNasabahLalu += isset($arrayKydBefore['DLL']['nasabah'])
                                ? $arrayKydBefore['DLL']['nasabah']
                                : 0;
                            $totalPersen += $percentage;

                        @endphp



                    </tbody>
                    <tr>
                        <th colspan="2" class="text-center table-success">Total</th>
                        <th class="text-center table-info">{{ number_format($totalNasabahLalu, 0, ',', '.') }}</th>
                        <th class="text-right table-info">{{ number_format($totalKYDLalu, 0, ',', '.') }}</th>
                        <th class="text-center table-warning">{{ number_format($totalNasabah, 0, ',', '.') }}
                        <th class="text-right table-warning">{{ number_format($totalKYD, 0, ',', '.') }}</th>
                        <th class="text-right table-primary ">
                            {{ number_format($totalKYD - $totalKYDLalu, 0, ',', '.') }}
                        </th>

                        <th class="text-right table-danger">
                            {{ number_format($totalPersen, 2) }}%
                        </th>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>
