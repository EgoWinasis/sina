@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - Register SLIK')

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">SLIK INDIVIDU</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">SLIK</li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>

                    <div class="row pb-5 d-flex justify-content-center">
                        <div class="col-xl-10">

                            <div class="card mb-4">
                                @if ($errors->any())
                                    <div class="card-header">
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                <div class="card-body">
                                    @if (count($dataSidaImport) > 0)
                                        <div class="row border-bottom">
                                            <div class="col-md-11 mb-3">
                                                @php
                                                    $options = [];
                                                    foreach ($dataSidaImport as $import) {
                                                        $id = $import->id;
                                                        $nama =
                                                            $import->nm .
                                                            ' - ' .
                                                            $import->ktp .
                                                            ' - ' .
                                                            $import->tm .
                                                            ' - ' .
                                                            $import->almt;
                                                        $options[$id] = $nama;
                                                    }

                                                    if (!empty(old('import_sida'))) {
                                                        $selected = [old('import_sida')];
                                                    } else {
                                                        $selected = ['1'];
                                                    }
                                                @endphp
                                                <x-adminlte-select2 name="import_sida" igroup-size="md"
                                                    label-class="text-lightblue" data-placeholder="Pilih Tipe..">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user-cog text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                    <x-adminlte-options :options="$options" :selected="$selected" />
                                                </x-adminlte-select2>

                                            </div>
                                            <div class="col-md-1"><button class="btn btn-success"
                                                    id="btn-import">Import</button></div>
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('slik.store') }}">
                                        @csrf
                                        <input type="hidden" name="tipe" value="individu">
                                        <x-adminlte-input readonly name="no_ref" label="Kode Referensi"
                                            value="{{ $no_ref }}" placeholder="{{ $no_ref }}"
                                            label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-shield-alt text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                        @php
                                            $tglPermintaan = date('Y-m-d');
                                        @endphp
                                        <x-adminlte-input readonly value="{{ $tglPermintaan }}" name="tgl_permintaan"
                                            label="Tanggal" placeholder="{{ $tglPermintaan }}" label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-alt text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                        <x-adminlte-input name="nik" label="NIK" id="nik"
                                            value="{{ old('nik') }}" placeholder="Nomor Induk Kependudukan"
                                            label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-address-card text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                        @php
                                            $options = [
                                                '1' => '01 - Penilaian Calon Debitur',
                                                '2' => '02 - Penerapan One Obligor Concept',
                                                '3' => '03 - Monitor Debitur Existing',
                                                '4' => '04 - Melayani Permintaan Debitur ',
                                                '5' => '05 - Dalam Rangka Pelaksanaan Audit',
                                                '6' => '06 - Penanganan Pengaduan Debitur',
                                                '7' => '07 - Penilaian Karyawan / Calon Karyawan',
                                                '8' => '08 - Penilaian Calon Vendor',
                                            ];

                                            if (!empty(old('tujuan_permintaan'))) {
                                                $selected = [old('tujuan_permintaan')];
                                            } else {
                                                $selected = ['1'];
                                            }
                                        @endphp
                                        <x-adminlte-select2 name="tujuan_permintaan" label="Tipe" igroup-size="md"
                                            label-class="text-lightblue" data-placeholder="Pilih Tipe..">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user-cog text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                            <x-adminlte-options :options="$options" :selected="$selected" />
                                        </x-adminlte-select2>

                                        <x-adminlte-input name="nama" label="Nama" id="nama"
                                            value="{{ old('nama') }}" placeholder="Nama Debitur"
                                            label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>

                                        <x-adminlte-input name="tgl_lahir" id="tgl_lahir" label="Tanggal Lahir"
                                            value="{{ old('tgl_lahir') }}" placeholder="contoh: 05121999"
                                            label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-alt text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>

                                        <x-adminlte-input name="tempat_lahir" id="tempat_lahir" label="Tempat Lahir"
                                            value="{{ old('tempat_lahir') }}" placeholder="Tempat Lahir"
                                            label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-city text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                        <x-adminlte-input name="alamat" id="almt" label="Alamat"
                                            value="{{ old('alamat') }}" placeholder="Alamat" label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-building text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                        @php
                                            if (auth()->user()->kantor == 'Kantor Pusat Operasional') {
                                                $options = [
                                                    'Adit' => 'Adit',
                                                    'Juwieta' => 'Juwieta',
                                                    'Nisa' => 'Nisa',
                                                ];

                                                if (!empty(old('petugas'))) {
                                                    $selected = [old('petugas')];
                                                } else {
                                                    $selected = ['Juwieta'];
                                                }
                                            } elseif (auth()->user()->kantor == 'Kantor Cabang Purwokerto') {
                                                $options = [
                                                    'Ainda' => 'Ainda',
                                                ];

                                                if (!empty(old('petugas'))) {
                                                    $selected = [old('petugas')];
                                                } else {
                                                    $selected = ['Ainda'];
                                                }
                                            }elseif (auth()->user()->kantor == 'Kantor Cabang Cilacap') {
                                                $options = [
                                                    'Retno' => 'Retno',
                                                ];

                                                if (!empty(old('petugas'))) {
                                                    $selected = [old('petugas')];
                                                } else {
                                                    $selected = ['Retno'];
                                                }
                                            }
                                        @endphp
                                        <x-adminlte-select2 name="petugas" label="Petugas" igroup-size="md"
                                            label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user-tie text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                            <x-adminlte-options :options="$options" :selected="$selected" />
                                        </x-adminlte-select2>

                                        <div class=" d-flex justify-content-center">
                                            <x-adminlte-button class="btn-flat" type="submit" label="Submit"
                                                theme="success" icon="fas fa-lg fa-save" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            @include('footer')

        </div>
    </div>

@stop



@section('css')
    <link
        href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.css"
        rel="stylesheet">
@endsection
@section('plugins.Select2', true)
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.js">
    </script>

    <script>
        function add() {
            window.location = "{{ route('device.create') }}";
        }
        $(document).ready(function() {
            var table = $('#table-device').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3] // Exclude the 4th column (Aksi)
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 3] // Exclude the 4th column (Aksi)
                        }
                    },

                ],
                searching: true,
                paging: true,
            });
        });
    </script>

    @if (count($dataSidaImport) > 0)
        <script>
            // import 
            $(document).ready(function() {
                // Handle Import button click
                $('#btn-import').on('click', function() {
                    // Get the selected value from the import_sida select
                    var selectedValue = $('[name="import_sida"]').val();

                    // Check if a value is selected
                    if (selectedValue) {
                        // Get the corresponding data from the options array
                        var selectedData = @json($dataSidaImport->keyBy('id')->all());
                        // split ttl
                        var tmField = selectedData[selectedValue]['tm'];
                        var tmFieldParts = tmField.split(', ');

                        $('#nama').val(selectedData[selectedValue]['nm']);
                        $('#nik').val(selectedData[selectedValue]['ktp']);
                        $('#almt').val(selectedData[selectedValue]['almt']);
                        $('#tempat_lahir').val(tmFieldParts[0]);
                        $('#tgl_lahir').val(tmFieldParts[1]);
                    }
                });
            });
        </script>
    @endif
@endsection
