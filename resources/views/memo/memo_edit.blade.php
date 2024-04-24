@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - MEMO SLIK')
{{-- @section('plugins.TempusDominusBs4', true)
@section('plugins.bsCustomFileInput', true) --}}
@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}
@endsection
@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Edit Memo</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">SLIK</li>
                        <li class="breadcrumb-item">Memo</li>
                        <li class="breadcrumb-item active">Edit</li>
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
                                    <form method="POST" action="{{ route('memo.update', $memo->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <x-adminlte-input name="marketing" required label="Marketing"
                                            value="{{ old('marketing', $memo->marketing) }}" placeholder="Nama Marketing"
                                            label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user-tie text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <x-adminlte-input required name="nama_debitur" label="Debitur"
                                                    value="{{ old('nama_debitur', $memo->nama_debitur) }}"
                                                    placeholder="Nama Debitur" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input required name="nik_debitur" label="NIK" type="number"
                                                    min=0 value="{{ old('nik_debitur', $memo->nik_debitur) }}"
                                                    placeholder="NIK" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-id-card text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input required name="tempat_lahir_debitur" label="Tempat Lahir"
                                                    value="{{ old('tempat_lahir_debitur', $memo->tempat_lahir_debitur) }}"
                                                    placeholder="Tempat Lahir" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-home text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input required name="tgl_lahir_debitur" class="tanggalInput"
                                                    label="Tanggal Lahir"
                                                    value="{{ old('tgl_lahir_debitur', $memo->tgl_lahir_debitur) }}"
                                                    placeholder="Tanggal Lahir" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar-alt text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input required name="alamat_debitur" label="Alamat Debitur"
                                                    value="{{ old('alamat_debitur', $memo->alamat_debitur) }}"
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
                                                <div class="text-center">
                                                    <label
                                                        class="small mb-2  text-red">{{ $memo->file_debitur ? 'File JSON Debitur Ready' : '' }}</label>
                                                </div>

                                            </div>
                                            <div class="col-xl-6">
                                                <x-adminlte-input name="nama_penjamin" label="Penjamin"
                                                    value="{{ old('nama_penjamin', $memo->nama_penjamin) }}"
                                                    placeholder="Nama Penjamin" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user-shield text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="nik_penjamin" label="NIK" type="number" min=0
                                                    value="{{ old('nik_penjamin', $memo->nik_penjamin) }}"
                                                    placeholder="NIK" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="tempat_lahir_penjamin" label="Tempat Lahir"
                                                    value="{{ old('tempat_lahir_penjamin', $memo->tempat_lahir_penjamin) }}"
                                                    placeholder="Tempat Lahir" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-home text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="tgl_lahir_penjamin" class="tanggalInput"
                                                    label="Tanggal Lahir"
                                                    value="{{ old('tgl_lahir_penjamin', $memo->tgl_lahir_penjamin) }}"
                                                    placeholder="Tanggal Lahir" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar-alt text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="alamat_penjamin" label="Alamat Penjamin"
                                                    value="{{ old('alamat_penjamin', $memo->alamat_penjamin) }}"
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
                                                <div class="text-center">
                                                    <label
                                                        class="small mb-2  text-red">{{ $memo->file_penjamin ? 'File JSON Penjamin Ready' : '' }}</label>
                                                </div>
                                            </div>

                                        </div>

                                        <div class=" d-flex justify-content-center">
                                            <x-adminlte-button class="btn-flat" type="submit" label="Save"
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
@endsection
