@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - MEMO SLIK')
{{-- @section('plugins.TempusDominusBs4', true)
@section('plugins.bsCustomFileInput', true) --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Tambah Memo</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">SLIK</li>
                        <li class="breadcrumb-item">Memo</li>
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
                                    <div class="row border-bottom">
                                        <div class="col-md-5 mb-3">
                                            @php
                                                $options = [];
                                                foreach ($register as $import) {
                                                    $id = $import->id;
                                                    $nama = $import->nama . ' - ' . $import->nik . ' - ' . $import->tempat_lahir . ', ' . $import->tgl_lahir . ' - ' . $import->alamat;
                                                    $options[$id] = $nama;
                                                }

                                                if (!empty(old('import_sida'))) {
                                                    $selected = [old('import_sida')];
                                                } else {
                                                    $selected = ['1'];
                                                }
                                            @endphp
                                            <x-adminlte-select2 name="import_sida-debitur" igroup-size="md"
                                                label-class="text-lightblue" data-placeholder="Pilih Tipe..">
                                                <x-slot name="prependSlot">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-user-cog text-lightblue"></i>
                                                    </div>
                                                </x-slot>
                                                <x-adminlte-options :options="$options" :selected="$selected" />
                                            </x-adminlte-select2>

                                        </div>
                                        <div class="col-md-1"><button id="btnImport-debitur" class="btn btn-success"
                                                id="btn-import">Import</button>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            @php
                                                $options = [];
                                                foreach ($register as $import) {
                                                    $id = $import->id;
                                                    $nama = $import->nama . ' - ' . $import->nik . ' - ' . $import->tempat_lahir . ', ' . $import->tgl_lahir . ' - ' . $import->alamat;
                                                    $options[$id] = $nama;
                                                }

                                                if (!empty(old('import_sida'))) {
                                                    $selected = [old('import_sida')];
                                                } else {
                                                    $selected = ['1'];
                                                }
                                            @endphp
                                            <x-adminlte-select2 name="import_sida-penjamin" igroup-size="md"
                                                label-class="text-lightblue" data-placeholder="Pilih Tipe..">
                                                <x-slot name="prependSlot">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-user-cog text-lightblue"></i>
                                                    </div>
                                                </x-slot>
                                                <x-adminlte-options :options="$options" :selected="$selected" />
                                            </x-adminlte-select2>

                                        </div>
                                        <div class="col-md-1"><button id="btnImport-penjamin" class="btn btn-success"
                                                id="btn-import">Import</button>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('memo.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id_register" id="id_register-debitur"
                                            value="{{ old('id_register') }}">
                                        <x-adminlte-input name="marketing" required label="Marketing"
                                            value="{{ old('marketing') }}" placeholder="Nama Marketing"
                                            label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user-tie text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <x-adminlte-input readonly required name="tipe_debitur" id="tipe-debitur"
                                                    label="Tipe Debitur" value="{{ old('tipe_debitur') }}"
                                                    placeholder="Tipe Debitur" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user-cog text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>

                                                <x-adminlte-input required name="nama_debitur" id="nama-debitur"
                                                    label="Debitur" value="{{ old('nama_debitur') }}"
                                                    placeholder="Nama Debitur" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input required name="nik_debitur" id="nik-debitur"
                                                    label="NIK" type="number" min=0 value="{{ old('nik_debitur') }}"
                                                    placeholder="NIK" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-id-card text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input required name="tempat_lahir_debitur"
                                                    id="tempat_lahir-debitur" label="Tempat Lahir"
                                                    value="{{ old('tempat_lahir_debitur') }}" placeholder="Tempat Lahir"
                                                    label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-home text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input required name="tgl_lahir_debitur" id="tgl_lahir-debitur"
                                                    class="tanggalInput" label="Tanggal Lahir"
                                                    value="{{ old('tgl_lahir_debitur') }}" placeholder="Tanggal Lahir"
                                                    label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar-alt text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input required name="alamat_debitur" id="alamat-debitur"
                                                    label="Alamat Debitur" value="{{ old('alamat_debitur') }}"
                                                    placeholder="Alamat Debitur" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-map-marker-alt text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input-file name="file_debitur" label="File JSON"
                                                    placeholder="Choose a file..." label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text bg-lightblue">
                                                            <i class="fas fa-upload"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input-file>
                                            </div>
                                            <div class="col-xl-6">
                                                <x-adminlte-input readonly required name="tipe_penjamin"
                                                    id="tipe-penjamin" label="Tipe Penjamin"
                                                    value="{{ old('tipe_penjamin') }}" placeholder="Tipe Penjamin"
                                                    label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user-cog text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="nama_penjamin" id="nama-penjamin"
                                                    label="Penjamin" value="{{ old('nama_penjamin') }}"
                                                    placeholder="Nama Penjamin" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user-shield text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="nik_penjamin" label="NIK" id="nik-penjamin"
                                                    type="number" min=0 value="{{ old('nik_penjamin') }}"
                                                    placeholder="NIK" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="tempat_lahir_penjamin" id="tempat_lahir-penjamin"
                                                    label="Tempat Lahir" value="{{ old('tempat_lahir_penjamin') }}"
                                                    placeholder="Tempat Lahir" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-home text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="tgl_lahir_penjamin" id="tgl_lahir-penjamin"
                                                    class="tanggalInput" label="Tanggal Lahir"
                                                    value="{{ old('tgl_lahir_penjamin') }}" placeholder="Tanggal Lahir"
                                                    label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar-alt text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="alamat_penjamin" id="alamat-penjamin"
                                                    label="Alamat Penjamin" value="{{ old('alamat_penjamin') }}"
                                                    placeholder="Alamat Penjamin" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-map-marker-alt text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input-file name="file_penjamin" label="File JSON"
                                                    placeholder="Choose a file..." label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text bg-lightblue">
                                                            <i class="fas fa-upload"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input-file>
                                            </div>

                                        </div>

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
@section('plugins.Select2', true)

@section('js')
    {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript">
        $(".tanggalInput").flatpickr();
    </script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    <script>
        // import 
        $(document).ready(function() {
            // Handle Import button click
            $('#btnImport-debitur').on('click', function() {
                // Get the selected value from the import_sida select
                var selectedValue = $('[name="import_sida-debitur"]').val();

                // Check if a value is selected
                if (selectedValue) {
                    // Get the corresponding data from the options array
                    var selectedData = @json($register->keyBy('id')->all());


                    $('#id_register-debitur').val(selectedData[selectedValue]['id']);
                    $('#nama-debitur').val(selectedData[selectedValue]['nama']);
                    $('#nik-debitur').val(selectedData[selectedValue]['nik']);
                    $('#alamat-debitur').val(selectedData[selectedValue]['alamat']);
                    $('#tempat_lahir-debitur').val(selectedData[selectedValue]['tempat_lahir']);
                    $('#tgl_lahir-debitur').val(selectedData[selectedValue]['tgl_lahir']);
                    // 
                    var tipeDebitur = selectedData[selectedValue]['tipe'];
                    var uppercaseTipeDebitur = tipeDebitur.toUpperCase();
                    $('#tipe-debitur').val(uppercaseTipeDebitur);



                    var nikDebiturLabel = $('label[for="nik-debitur"]');
                    var tempatDebiturLabel = $('label[for="tempat_lahir-debitur"]');
                    var tglDebiturLabel = $('label[for="tgl_lahir-debitur"]');
                    // Change the for attribute based on the value of tipeDebitur
                    if (tipeDebitur === 'perusahaan') {
                        nikDebiturLabel.text('NPWP');
                        tempatDebiturLabel.text('Tempat Pendirian');
                        tglDebiturLabel.text('Tanggal Pendirian');
                    } else {
                        nikDebiturLabel.text('NIK');
                        tempatDebiturLabel.text('Tempat Lahir');
                        tglDebiturLabel.text('Tanggal Lahir');
                    }
                }
            });

            $('#btnImport-penjamin').on('click', function() {
                // Get the selected value from the import_sida select
                var selectedValue = $('[name="import_sida-penjamin"]').val();

                // Check if a value is selected
                if (selectedValue) {
                    // Get the corresponding data from the options array
                    var selectedData = @json($register->keyBy('id')->all());


                    $('#nama-penjamin').val(selectedData[selectedValue]['nama']);
                    $('#nik-penjamin').val(selectedData[selectedValue]['nik']);
                    $('#alamat-penjamin').val(selectedData[selectedValue]['alamat']);
                    $('#tempat_lahir-penjamin').val(selectedData[selectedValue]['tempat_lahir']);
                    $('#tgl_lahir-penjamin').val(selectedData[selectedValue]['tgl_lahir']);
                    // 
                    var tipePenjamin = selectedData[selectedValue]['tipe'];
                    var uppercaseTipePenjamin = tipePenjamin.toUpperCase();
                    $('#tipe-penjamin').val(uppercaseTipePenjamin);


                    var nikPenjaminLabel = $('label[for="nik-penjamin"]');
                    var tempatPenjaminLabel = $('label[for="tempat_lahir-penjamin"]');
                    var tglPenjaminLabel = $('label[for="tgl_lahir-penjamin"]');
                    // Change the for attribute based on the value of tipePenjamin
                    if (tipePenjamin === 'perusahaan') {
                        nikPenjaminLabel.text('NPWP');
                        tempatPenjaminLabel.text('Tempat Pendirian');
                        tglPenjaminLabel.text('Tanggal Pendirian');
                    } else {
                        nikPenjaminLabel.text('NIK');
                        tempatPenjaminLabel.text('Tempat Lahir');
                        tglPenjaminLabel.text('Tanggal Lahir');
                    }
                }
            });


        });
    </script>
@endsection
