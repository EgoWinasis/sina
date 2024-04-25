@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - MEMO SLIK')

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Memo</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">SLIK</li>
                        <li class="breadcrumb-item active">Memo</li>
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
                                <div class="card-body table-responsive">
                                    <table id="table-device" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Marketing</th>
                                                <th>NIK Debitur</th>
                                                <th>Debitur</th>
                                                {{-- <th>NIK Penjamin</th>
                                                <th>Penjamin</th> --}}
                                                <th>Alamat Debitur</th>
                                                <th>Memo</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($memos as $memo)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($memo->created_at)) }}</td>
                                                    <td>{{ $memo->marketing }}</td>
                                                    <td>{{ $memo->nik_debitur }}</td>
                                                    <td>{{ $memo->nama_debitur }}</td>
                                                    {{-- <td>{{ $memo->nik_penjamin }}</td>
                                                    <td>{{ $memo->nama_penjamin }}</td> --}}
                                                    <td>{{ $memo->alamat_debitur }}</td>
                                                    <td class="text-center">
                                                        <a class="btn btn-secondary" target="_blank"
                                                            href="{{ route('memo.cetak', $memo->id) }}">
                                                            <i class="far fa-file-pdf"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn btn-info m-2" data-toggle="modal"
                                                            data-target="#memoModal" data-memo-id="{{ $memo->id }}">
                                                            Show</a>
                                                        <a class="btn btn-primary m-2"
                                                            href="{{ route('memo.edit', $memo->id) }}">Edit</a>
                                                        {{-- <a class="btn btn-danger btn-delete m-2">Delete</a> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                {{-- modal --}}

                <!-- Modal -->
                <div class="modal fade" id="memoModal" tabindex="-1" role="dialog" aria-labelledby="memoModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="memoModalLabel">Memo Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Content loaded via AJAX will be inserted here -->
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
            window.location = "{{ route('memo.create') }}";
        }
        $(document).ready(function() {
            var table = $('#table-device').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5] // Exclude the 4th column (Aksi)
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5] // Exclude the 4th column (Aksi)
                        }
                    },

                ],
                searching: true,
                'ordering': false,
                paging: true,
                columnDefs: [{
                    width: '20%',
                    targets: 7
                }],
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Triggered when the modal is about to be shown
            $('#memoModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var memoId = button.data('memo-id');

                // Make an AJAX request to get memo details
                $.ajax({
                    type: 'GET',
                    url: '/memo/' + memoId,
                    dataType: 'json',
                    contentType: 'application/json; charset=utf-8',
                    success: function(response) {
                        // console.log(response.data);
                        // Update the modal content with the data received from the server
                        if (response && response.data) {
                            var debitur = response.data[0];
                            // Update the modal content with the received data for the debtor
                            var html = '<table class="table" width="100%">';
                            for (var key in debitur) {
                                if (key !== 'id' && key !== 'id_register' && key !==
                                    'file_debitur' && key !== 'file_penjamin') {
                                    var value = debitur[key] !== null ? debitur[key] :
                                    ""; // Replace null with empty string
                                    html += '<tr><td width="30%">' + key.replace(/_/g, ' ')
                                        .toUpperCase() + '</td><td>:</td><td>' + value +
                                        '</td></tr>';
                                }
                            }

                            html += '</table>';
                            $('#memoModal .modal-body').html(html);



                            // Similarly, you can access and display data for the guarantor if needed
                        } else {
                            console.log('Invalid response format or missing data');
                            // Optionally, show an error message or handle the situation differently
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>

@endsection
