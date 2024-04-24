<div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
@if (isset($droping['count_spk']) && isset($droping['sum_plafon']) && isset($droping['sum_plafon_today']))
    
<div class="row">
    <div class="col-xl-4 col-md-6">

        <div class="card bg-primary text-white mb-4">
            <div class="card-body d-flex  justify-content-between">
                <span>Nasabah</span>
                <span style="font-weight: bold">{{ $droping['count_spk'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body d-flex  justify-content-between">
                <span>Droping Bulan Ini </span>
                <span style="font-weight: bold">Rp.
                    {{ number_format($droping['sum_plafon'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body d-flex  justify-content-between">
                <span>Droping Hari Ini </span>
                <span style="font-weight: bold">Rp.
                    {{ number_format($droping['sum_plafon_today'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
   
</div>
@endif
   
    @include('table.droping_ao')

</div>

