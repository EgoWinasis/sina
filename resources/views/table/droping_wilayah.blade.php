@php
    use Carbon\Carbon;

    // Get the first day of the previous month
    $firstDayOfPreviousMonth = Carbon::now()->startofMonth()->subMonth();

    // Get the month and year components
    $currentMonth = Carbon::now()->format('m');
    $previousMonth = $firstDayOfPreviousMonth->format('m');
    $currentYear = Carbon::now()->format('Y');
    // jenis
    $allowedJnsValues = ['L', 'DPK', 'KL', 'DR', 'M'];
    $modelKantorData = \App\Models\ModelKantor::all();
    // Query to get and sum the 'jml' column for the current month and year
    $sumKydlalu = \App\Models\Models\KydModel::where('bln', $previousMonth)
        ->where('thn', $currentYear)
        ->whereIn('jns', $allowedJnsValues)
        ->where(function ($query) {
            $query
                ->where('cab', 1)
                ->whereIn('kode', ['OP', 'A1', 'A2', 'A3', 'A5'])
                ->orWhere(function ($query) {
                    $query->whereIn('cab', [2, 3])->where('kode', 'OP');
                });
        })
        ->groupBy('cab', 'kode')
        ->selectRaw('cab, kode, sum(jml) as total_jml')
        ->get();

    $sumKyd = \App\Models\Models\KydModel::where('bln', $currentMonth)
        ->where('thn', $currentYear)
        ->whereIn('jns', $allowedJnsValues)
        ->where(function ($query) {
            $query
                ->where('cab', 1)
                ->whereIn('kode', ['OP', 'A1', 'A2', 'A3', 'A5'])
                ->orWhere(function ($query) {
                    $query->whereIn('cab', [2, 3])->where('kode', 'OP');
                });
        })
        ->groupBy('cab', 'kode')
        ->selectRaw('cab, kode, sum(jml) as total_jml')
        ->get();

    $kydlaluArray = [];
    $kydArray = [];

    // Iterate through the results and store them in the array
    foreach ($sumKydlalu as $result) {
        // Adjust the 'kode' value based on the 'cab' value
        if ($result->cab == 2) {
            $result->kode = 'OP2';
        } elseif ($result->cab == 3) {
            $result->kode = 'OP3';
        }

        // Store the result in the array using 'cab' as the key
        $kydlaluArray[$result->kode] = [
            'total_jml' => $result->total_jml,
        ];
    }
    // Iterate through the results and store them in the array
    foreach ($sumKyd as $result) {
        // Adjust the 'kode' value based on the 'cab' value
        if ($result->cab == 2) {
            $result->kode = 'OP2';
        } elseif ($result->cab == 3) {
            $result->kode = 'OP3';
        }

        // Store the result in the array using 'cab' as the key
        $kydArray[$result->kode] = [
            'total_jml' => $result->total_jml,
        ];
    }
    // var_dump($kydArray);
    // Add calculated sums as additional fields to each item
    $dataWilayah = [];
    foreach ($modelKantorData as $item) {
        $dataWilayah[$item['kantor']] = [
            'droping' => 0,
            'dropingToday' => 0,
            'nasabah' => 0,
            'kydlalu' => 0,
            'kyd' => 0,
        ];
    }

    // Iterate through the data and calculate sums
    foreach ($results as $item) {
        
        if (in_array($item['kode_ao'], ['RK', 'BN', 'ML'])) {
            $dataWilayah['Kantor Pusat Operasional']['droping'] += $item['sum_plafon'];
            $dataWilayah['Kantor Pusat Operasional']['dropingToday'] += $item['sum_plafon_today'];
            $dataWilayah['Kantor Pusat Operasional']['nasabah'] += $item['count_spk'];
            $dataWilayah['Kantor Pusat Operasional']['kydlalu'] = $kydlaluArray['OP']['total_jml'];
            $dataWilayah['Kantor Pusat Operasional']['kyd'] = $kydArray['OP']['total_jml'] ?? 0;
        } elseif (in_array($item['kode_ao'], ['TE', 'DT'])) {
            $dataWilayah['Kantor Cabang Purwokerto']['droping'] += $item['sum_plafon'];
            $dataWilayah['Kantor Cabang Purwokerto']['dropingToday'] += $item['sum_plafon_today'];
            $dataWilayah['Kantor Cabang Purwokerto']['nasabah'] += $item['count_spk'];
            $dataWilayah['Kantor Cabang Purwokerto']['kydlalu'] = $kydlaluArray['OP2']['total_jml'];
            $dataWilayah['Kantor Cabang Purwokerto']['kyd'] = $kydArray['OP2']['total_jml'] ?? 0;
        } elseif (in_array($item['kode_ao'], ['LG', 'RB', 'AY'])) {
            $dataWilayah['Kantor Cabang Cilacap']['droping'] += $item['sum_plafon'];
            $dataWilayah['Kantor Cabang Cilacap']['dropingToday'] += $item['sum_plafon_today'];
            $dataWilayah['Kantor Cabang Cilacap']['nasabah'] += $item['count_spk'];
            $dataWilayah['Kantor Cabang Cilacap']['kydlalu'] = $kydlaluArray['OP3']['total_jml'];
            $dataWilayah['Kantor Cabang Cilacap']['kyd'] = $kydArray['OP3']['total_jml'] ?? 0;
        } elseif ($item['kode_ao'] == 'BR') {
            $dataWilayah['Kantor Kas Lebaksiu']['droping'] += $item['sum_plafon'];
            $dataWilayah['Kantor Kas Lebaksiu']['dropingToday'] += $item['sum_plafon_today'];
            $dataWilayah['Kantor Kas Lebaksiu']['nasabah'] += $item['count_spk'];
            $dataWilayah['Kantor Kas Lebaksiu']['kydlalu'] = $kydlaluArray['A3']['total_jml'];
            $dataWilayah['Kantor Kas Lebaksiu']['kyd'] = $kydArray['A3']['total_jml'] ?? 0;
        } elseif ($item['kode_ao'] == 'TN') {
            $dataWilayah['Kantor Kas Dukuhturi']['droping'] += $item['sum_plafon'];
            $dataWilayah['Kantor Kas Dukuhturi']['dropingToday'] += $item['sum_plafon_today'];
            $dataWilayah['Kantor Kas Dukuhturi']['nasabah'] += $item['count_spk'];
            $dataWilayah['Kantor Kas Dukuhturi']['kydlalu'] = $kydlaluArray['A2']['total_jml'];
            $dataWilayah['Kantor Kas Dukuhturi']['kyd'] = $kydArray['A2']['total_jml'] ?? 0;
        } elseif ($item['kode_ao'] == 'MS') {
            $dataWilayah['Kantor Kas Dukuhwaru']['droping'] += $item['sum_plafon'];
            $dataWilayah['Kantor Kas Dukuhwaru']['dropingToday'] += $item['sum_plafon_today'];
            $dataWilayah['Kantor Kas Dukuhwaru']['nasabah'] += $item['count_spk'];
            $dataWilayah['Kantor Kas Dukuhwaru']['kydlalu'] = $kydlaluArray['A5']['total_jml'];
            $dataWilayah['Kantor Kas Dukuhwaru']['kyd'] = $kydArray['A5']['total_jml'] ?? 0;
        } elseif ($item['kode_ao'] == 'MA') {
            $dataWilayah['Kantor Kas Tarub']['droping'] += $item['sum_plafon'];
            $dataWilayah['Kantor Kas Tarub']['dropingToday'] += $item['sum_plafon_today'];
            $dataWilayah['Kantor Kas Tarub']['nasabah'] += $item['count_spk'];
            $dataWilayah['Kantor Kas Tarub']['kydlalu'] = $kydlaluArray['A1']['total_jml'];
            $dataWilayah['Kantor Kas Tarub']['kyd'] = $kydArray['A1']['total_jml'] ?? 0;
        }
    }

@endphp
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <div class="col-md-10">
                    <span>
                        <i class="fas fa-table mr-1"></i>Table Droping Kantor -
                        {{ now()->locale('id_ID')->isoFormat('D MMMM Y') }}
                    </span>
                </div>
            </div>



            <div class="card-body table-responsive">
                <table class="table table-striped table-hover table-borderless" id="table-wilayah"
                    style="font-size: 1.5rem">
                    <thead>
                        <tr>
                            <th rowspan="2" class="table-success text-center">Wilayah</th>
                            <th colspan="2" class="table-warning text-center">Droping Bulan Ini</th>
                            <th rowspan="2" class="table-info text-center">KYD Bulan Lalu</th>
                            <th rowspan="2" class="table-info text-center">KYD Bulan Ini</th>
                            <th rowspan="2" class="table-primary text-center">Tumbuh</th>
                        </tr>
                        <tr>
                            <th class="table-warning text-center">Nasabah</th>
                            <th class="table-warning text-center">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalKYD = 0;
                            $totalKYDLalu = 0;
                            $totalNasabah = 0;
                            $totalDroping = 0;
                        @endphp
                        @foreach ($dataWilayah as $office => $data)
                            <tr>
                                <td>{{ $office }}</td>
                                <td class="text-center">{{ $data['nasabah'] }}</td>
                                @if ( $data['droping'] != 0)
                                <td class="text-right">{{ number_format(substr($data['droping'], 0, -3), 0, ',', '.') }}
                                @else   
                                <td class="text-right">{{ number_format($data['droping'], 0, ',', '.') }}
                                @endif
                                </td>
                                <td class="text-right">{{ number_format($data['kydlalu'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($data['kyd'], 0, ',', '.') }}</td>
                                @if ($data['kyd'] - $data['kydlalu'] > 0)
                                    <td class="text-right d-flex justify-content-between">
                                        <i class="text-success fas fa-arrow-circle-up"></i>
                                        <span> {{ number_format($data['kyd'] - $data['kydlalu'], 0, ',', '.') }}</span>
                                    </td>
                                @else
                                    <td class="text-right d-flex justify-content-between">
                                        <i class=" text-danger fas fa-arrow-circle-down"></i>
                                        <span> {{ number_format($data['kyd'] - $data['kydlalu'], 0, ',', '.') }}</span>
                                    </td>
                                @endif
                            </tr>
                            @php
                                $totalDroping += $data['droping'];
                                $totalNasabah += $data['nasabah'];
                                $totalKYDLalu += $data['kydlalu'];
                                $totalKYD += $data['kyd'];
                            @endphp
                        @endforeach
                    </tbody>
                    <tr>
                        <th class="table-success text-center">Total</th>
                        <th class="table-warning text-center">{{ $totalNasabah }}</th>
                        <th class="table-warning text-right">
                            {{ number_format(substr($totalDroping, 0, -3), 0, ',', '.') }}</th>
                        <th class="table-info text-right">{{ number_format($totalKYDLalu, 0, ',', '.') }}</th>
                        <th class="table-info text-right">{{ number_format($totalKYD, 0, ',', '.') }}</th>
                        @if ($totalKYD - $totalKYDLalu > 0)
                            <th class="table-primary text-right d-flex justify-content-between">
                                <i class="text-success fas fa-arrow-circle-up"></i>
                                <span> {{ number_format($totalKYD - $totalKYDLalu, 0, ',', '.') }}</span>
                            </th>
                        @else
                            <th class="table-primary text-right d-flex justify-content-between">
                                <i class=" text-danger fas fa-arrow-circle-down"></i>
                                <span> {{ number_format($totalKYD - $totalKYDLalu, 0, ',', '.') }}</span>
                            </th>
                        @endif
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>
