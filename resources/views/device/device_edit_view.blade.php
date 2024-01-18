@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - Display Device')

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Device</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Display</li>
                        <li class="breadcrumb-item">Device</li>
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
                                    <form method="POST" action="{{ route('device.update', $device[0]->id) }}">
                                        @method('PUT')
                                        @csrf
                                        <x-adminlte-input name="device" label="Device" value="{{ old('device', $device[0]->device) }}"
                                            placeholder="Display Device" label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-desktop text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                    
                                        <x-adminlte-input name="user" label="User" value="{{ old('user', $device[0]->user) }}"
                                            placeholder="User" label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user-shield text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                    
                                        <x-adminlte-input name="pemilik" label="Pemilik" value="{{ old('pemilik', $device[0]->pemilik) }}"
                                            placeholder="Pemilik" label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                    
                                        <!-- Add other fields as needed -->
                                    
                                        @php
                                        $options = [
                                            'Kantor Pusat Operasional Adiwerna' => 'Kantor Pusat Operasional Adiwerna',
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
                                            $selected = [$device[0]->kantor];
                                        }
                                        @endphp
                                    
                                        <x-adminlte-select2 name="kantor" label="Kantor" igroup-size="md" label-class="text-lightblue"
                                            data-placeholder="Pilih Kantor..">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-building text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                            <x-adminlte-options :options="$options" :selected="$selected" />
                                        </x-adminlte-select2>
                                    
                                        <div class=" d-flex justify-content-center">
                                            <x-adminlte-button class="btn-flat" type="submit" label="Submit" theme="success"
                                                icon="fas fa-lg fa-save" />
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto fixed-bottom ">
                <div class="container-fluid ">
                    <div class="d-flex align-items-center justify-content-center small">
                        <div class="text-muted ">Copyright &copy; IT BPR Nusamba Adiwerna {!! date('Y') !!}</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

@stop



@section('css')
    <link
        href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.css"
        rel="stylesheet">
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.js">
    </script>


@endsection
