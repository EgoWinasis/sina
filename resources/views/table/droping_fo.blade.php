
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <div class="col-md-10">
                    <span>
                        <i class="fas fa-table mr-1"></i>Table Monitoring FO -
                        {{ now()->locale('id_ID')->isoFormat('D MMMM Y') }}  <span class="text-danger">ON DEVELOPMENT</span>
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
                            <th class="table-warning"></th>
                            <th class="table-warning"></th>
                            <th class="table-warning"></th>
                            <th class="table-primary" colspan="2">Target Bulan Ini</th>
                            <th class="table-danger" colspan="2">Pencapaian</th>
                            
                        </tr>
                        <tr>
                            <th class="table-success text-center">NO</th>
                            <th class="table-success text-center">FO</th>
                            <th class="table-warning text-center">Trx EDC Hr ini </th>
                            <th class="table-warning text-center">Trx EDC Bulan ini</th>
                            <th class="table-warning text-center">Nsbh Baru Bln ini</th>
                            <th class="table-primary text-center">Transaksi EDC</th>
                            <th class="table-primary text-center">Nasabah Baru</th>
                            <th class="table-danger text-center">Transaksi EDC</th>
                            <th class="table-danger text-center">Nasabah Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                    
                </table>

            </div>
        </div>
    </div>
</div>
