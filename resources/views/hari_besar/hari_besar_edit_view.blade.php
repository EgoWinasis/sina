@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - Hari Besar')

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Hari Besar</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Desain</li>
                        <li class="breadcrumb-item">Hari Besar</li>
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
                                    <form method="POST" action="{{ route('hari-besar.update', $hariBesar[0]->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT') <!-- Add this line to spoof the PUT method -->
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <x-adminlte-input name="hari" label="Hari"
                                                    value="{{ $hariBesar[0]->hari }}" placeholder="Hari"
                                                    label-class="text-lightblue">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-birthday-cake text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
                                                <x-adminlte-input name="tanggal" label="Tanggal"
                                                    value="{{ $hariBesar[0]->tanggal }}" placeholder=""
                                                    label-class="text-lightblue" class="tanggal">
                                                    <x-slot name="prependSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar text-lightblue"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input>
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
    </script>

@endsection
