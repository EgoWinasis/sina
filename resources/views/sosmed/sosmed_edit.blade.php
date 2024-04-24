@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - Sosial Media')

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Sosial Media</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Akun</li>
                        <li class="breadcrumb-item">Sosial Media</li>
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
                                    <form method="POST" action="{{ route('sosmed.update', $sosmed[0]->id) }}">
                                        @method('PUT')
                                        @csrf
                                        <x-adminlte-input name="platform" label="Platform" value="{{ old('platform') ?? $sosmed[0]->platform }}"
                                            placeholder="Nama Platform" label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-desktop text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                        <x-adminlte-input name="username" label="Username" value="{{ old('username')?? $sosmed[0]->username }}"
                                            placeholder="Username" label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                        <x-adminlte-input name="password" label="Password" value="{{ old('password') }}"
                                            placeholder="Password" label-class="text-lightblue">
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text">
                                                    <i class="fas fa-key text-lightblue"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
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
                                                $selected = $sosmed[0]->kantor;
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
