@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - Banner')

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Banner</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Desain</li>
                        <li class="breadcrumb-item">Banner</li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>

                    <div class="row pb-5 d-flex justify-content-center">
                        <div class="col-xl-12">

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
                                    <form method="POST" action="{{ route('banner.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-6">
                                                @php
                                                    $options = [
                                                        'Kantor Pusat Operasional Adiwerna' =>
                                                            'Kantor Pusat Operasional Adiwerna',
                                                        'Kantor Cabang Purwokerto' => 'Kantor Cabang Purwokerto',
                                                        'Kantor Cabang Cilacap' => 'Kantor Cabang Cilacap',
                                                        'Kantor Kas Tarub' => 'Kantor Kas Tarub',
                                                        'Kantor Kas Dukuhturi' => 'Kantor Kas Dukuhturi',
                                                        'Kantor Kas Dukuhwaru' => 'Kantor Kas Dukuhwaru',
                                                        'Kantor Kas Lebaksiu' => 'Kantor Kas Lebaksiu',
                                                    ];

                                                    if (!empty(old('kantor'))) {
                                                        $selected = [old('kantor')];
                                                    } else {
                                                        $selected = ['Kantor Pusat Operasional Adiwerna'];
                                                    }
                                                @endphp
                                                <x-adminlte-select2 name="kantor" label="Kantor" igroup-size="md"
                                                    label-class="text-lightblue" data-placeholder="Pilih Kantor..">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-building text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                    <x-adminlte-options :options="$options" :selected="$selected" />
                                                </x-adminlte-select2>

                                                <x-adminlte-input name="alamat" label="Alamat" value="{{ old('alamat') }}"
                                                    placeholder="Alamat" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-map-marker-alt text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="panjang" label="Panjang"
                                                    value="{{ old('panjang') }}" placeholder="Cm"
                                                    label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-ruler-horizontal text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="hp" label="HP" value="{{ old('hp') }}"
                                                    placeholder="08xxxxxxxxxx" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-phone text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                    
                                                </x-adminlte-input>
                                            </div>
                                            {{--  --}}
                                            <div class="col-xl-6">
                                                <x-adminlte-input name="toko" label="Toko" value="{{ old('toko') }}"
                                                    placeholder="Toko" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-home text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="deskripsi" label="Deskripsi"
                                                    value="{{ old('deskripsi') }}" placeholder="Deskripsi"
                                                    label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-book text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="lebar" label="Lebar" value="{{ old('lebar') }}"
                                                    placeholder="Cm" label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-ruler-vertical text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="deadline" label="Deadline"
                                                    value="{{ old('deadline') }}" placeholder=""
                                                    label-class="text-lightblue" class="tanggal">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                            </div>

                                            {{--  --}}

                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">

                                                <div class="form-group">
                                                    <label for="imgInp" class="text-lightblue">Gambar</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="imgInp"
                                                                name="image">
                                                            <label class="custom-file-label" for="imgInp">Choose a
                                                                image...</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <button class="btn bg-lightblue" type="button"><i
                                                                    class="fas fa-upload"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-xl-6">
                                                <img id="image" src="{{ asset('storage/banner/image-default.png') }}"
                                                    alt="gambar" class="rounded mx-auto d-block mt-2" width="200px">
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

@section('css')
    <link
        href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.js">
    </script>

    <script>
        function add() {
            window.location = "{{ route('device.create') }}";
        }
        // flatpicker

        $(".tanggal").flatpickr();
        // 
        // input
        document.getElementById('hp').addEventListener('input', function(event) {
            // Remove non-numeric characters
            this.value = this.value.replace(/\D/g, '');
        });
        document.getElementById('panjang').addEventListener('input', function(event) {
            // Remove non-numeric characters
            this.value = this.value.replace(/\D/g, '');
        });
        document.getElementById('lebar').addEventListener('input', function(event) {
            // Remove non-numeric characters
            this.value = this.value.replace(/\D/g, '');
        });
        // image
        imgInp.onchange = evt => {
            const [file] = imgInp.files
            if (file) {
                image.src = URL.createObjectURL(file)
            }
        }
        // 
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
                    {
                        extend: 'print',
                        text: 'Print',
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
@endsection
