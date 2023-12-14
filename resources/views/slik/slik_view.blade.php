@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - SLIK')

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">SLIK</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">SLIK</li>
                        <li class="breadcrumb-item active">Register</li>
                    </ol>

                    <div class="row pb-5 d-flex justify-content-center">
                        <div class="col-xl-12">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <x-adminlte-button onclick="return add();" label="Tambah" theme="primary"
                                            icon="fas fa-plus" />
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="table-device" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Referensi</th>
                                                <th>Tgl Permintaan</th>
                                                <th>NIK</th>
                                                <th>Tujuan Permintaan</th>
                                                <th>Nama</th>
                                                <th>Tgl Lahir</th>
                                                <th>Tempat Lahir</th>
                                                <th>Petugas</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($devices as $device)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{$device->device}}</td>
                                                    <td>{{$device->user}}</td>
                                                    <td>{{$device->pemilik}}</td>
                                                    <td>{{$device->kantor}}</td>
                                                    <td>
                                                        <a class="btn btn-info">Show</a>
                                                        <a class="btn btn-primary">Edit</a>
                                                        <a class="btn btn-danger btn-delete">Delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach --}}
                                        </tbody>

                                    </table>
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
