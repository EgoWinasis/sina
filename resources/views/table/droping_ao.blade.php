<div class="row">
    <div class="col-xl-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <div class="col-md-10">
                    <span>
                        <i class="fas fa-table mr-1"></i>Table Droping -
                        {{ now()->locale('id_ID')->isoFormat('D MMMM Y') }}
                    </span>
                </div>
                @if (Auth::user()->role == 'super')      
                <div class="col-md-2 text-right">
                    <i title="Tambah Droping AO Baru" class="fas fa-plus" style="cursor: pointer" id="modalAoBaru"></i>
                </div>
                @endif
            </div>



            <div class="card-body table-responsive">
                <table class="table table-striped table-hover table-borderless" id="table-droping">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th colspan="3" class="table-info text-center">Jumlah Nasabah</th>
                            <th colspan="2" class="table-warning text-center">Droping Hari Ini</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="table-success text-center">AO</th>
                            <th class="table-success text-center">Nama</th>
                            <th class="table-success text-center">Droping Bulan Ini</th>
                            <th class="table-info text-center">Lama</th>
                            <th class="table-info text-center">Baru</th>
                            <th class="table-info text-center">Total</th>
                            <th class="table-warning text-center">Nasabah</th>
                            <th class="table-warning text-center">Nominal</th>
                            <th class="table-success text-center">Pending</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalKYD = 0;
                            $totalKYDToday = 0;
                            $totalPending = 0;
                            $totalNasabah = 0;
                            $totalNasabahToday = 0;
                            $totalNsbhBaru = 0;
                            $totalNsbhLama = 0;

                            
                        @endphp
                        @foreach ($results as $item)
                            <tr>
                                <td class="text-center">{{ $item['kode_ao'] }}</td>
                                <td class="text-left">{{ Illuminate\Support\Str::upper($item['nama_ao']) }}</td>
                                {{-- <td class="text-right">{{ number_format($item['sum_plafon'], 0, ',', '.') }}</td> --}}
                                <td class="text-right">{{ $item['sum_plafon'] }}</td>
                                <td class="text-center">{{ $item['nsbhLama'] }}</td>
                                <td class="text-center">{{ $item['nsbhBaru'] }}</td>
                                <td class="text-center">{{ $item['count_spk'] }}</td>
                                <td class="text-center">{{ $item['count_spk_today'] }}</td>
                                <td class="text-right">{{ number_format($item['sum_plafon_today'], 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    {{ number_format($item['sum_plafon_pending'], 0, ',', '.') }}</td>
                            </tr>
                            @php
                                $totalKYD += $item['sum_plafon'];
                                $totalKYDToday += $item['sum_plafon_today'];
                                $totalPending += $item['sum_plafon_pending'];
                                $totalNasabah += $item['count_spk'];
                                $totalNasabahToday += $item['count_spk_today'];
                                $totalNsbhBaru += $item['nsbhBaru'];
                                $totalNsbhLama += $item['nsbhLama'];

                            @endphp
                        @endforeach
                    </tbody>
                    <tr>
                        <th colspan="2" class="text-center table-success">Total</th>
                        <th class="text-right table-success">{{ number_format($totalKYD, 0, ',', '.') }}</th>
                        <th class="text-center table-info">{{ $totalNsbhLama }}</th>
                        <th class="text-center table-info">{{ $totalNsbhBaru }}</th>
                        <th class="text-center table-info">{{ number_format($totalNasabah, 0, ',', '.') }}</th>
                        <th class="text-center table-warning">{{ number_format($totalNasabahToday, 0, ',', '.') }}
                        </th>
                        <th class="text-right table-warning">{{ number_format($totalKYDToday, 0, ',', '.') }}</th>
                        <th class="text-center table-success">{{ number_format($totalPending, 0, ',', '.') }}</th>
                    </tr>
                </table>
                <div class="row mt-2">
                    <div class="col-6">
                        <table class="table table-striped table-hover table-borderless">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th colspan="2" class="table-info text-center">Droping Bulan Ini</th>
                                    <th colspan="2" class="table-warning text-center">Droping Hari Ini</th>
                                </tr>
                                <tr>
                                    <th class="table-success text-center">AO Baru</th>
                                    <th class="table-info text-center">Nominal</th>
                                    <th class="table-info text-center">Nasabah</th>
                                    <th class="table-warning text-center">Nominal</th>
                                    <th class="table-warning text-center">Nasabah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($resultsAobaru))
                                    @foreach ($resultsAobaru as $item)
                                        @if ($item['kode_ao'] == 'rc')
                                            <tr>
                                                <td>Rico (Purwokerto)</td>
                                                <td class="text-right">
                                                    {{ number_format($item['sum_plafon'], 0, ',', '.') }}</td>
                                                <td class="text-center">{{ $item['count_spk'] }}</td>
                                                <td class="text-right">
                                                    {{ number_format($item['sum_plafon_today'], 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">{{ $item['count_spk_today'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td>Rico (Purwokerto)</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endif
                            </tbody>
                            <!-- Add your table body content here -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
