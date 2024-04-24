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

                                        {{-- <x-adminlte-button onclick="return add();" label="Tambah" theme="primary"
                                            icon="fas fa-plus" /> --}}

                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fas fa-plus"></i> Tambah
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <button class="dropdown-item width-auto"
                                                    onclick="setCookieAndRedirect('individu');">Individu</button>
                                                <button class="dropdown-item width-auto"
                                                    onclick="setCookieAndRedirect('perusahaan');">Perusahaan</button>
                                            </div>
                                        </div>

                                        {{-- export --}}
                                        <x-adminlte-button onclick="exportSelectedRows()" label="Export"
                                            title="Export for batch" theme="success" class="ml-4" icon='fas fa-file-alt'>
                                        </x-adminlte-button>

                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">

                                        <table id="table-device" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Referensi</th>
                                                    <th>Tgl Permintaan</th>
                                                    <th>NIK</th>
                                                    <th>Nama</th>
                                                    <th>Tempat Lahir</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>Tujuan Permintaan</th>
                                                    <th>Checked</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($register as $regis)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $regis->no_ref }}</td>
                                                        <td>{{ $regis->tgl_permintaan }}</td>
                                                        <td>{{ $regis->nik }}</td>
                                                        <td>{{ $regis->nama }}</td>
                                                        <td>{{ $regis->tempat_lahir }}</td>
                                                        <td>{{ $regis->tgl_lahir }}</td>
                                                        <td class="text-center">{{ $regis->tujuan_permintaan }}</td>
                                                        <td class="text-center"><input type="checkbox"></td>
                                                        <td class="text-center">
                                                            <a class="btn btn-info m-2" data-toggle="modal"
                                                                data-target="#slikModal"
                                                                data-regis-id="{{ $regis->id }}">
                                                                Show</a>
                                                            <a class="btn btn-primary m-2"
                                                                href="{{ route('slik.edit', $regis->id) }}">Edit</a>
                                                            {{-- <a class="btn btn-danger m-2 btn-delete">Delete</a> --}}
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
                </div>


                {{-- modal --}}

                <!-- Modal -->
                <div class="modal fade" id="slikModal" tabindex="-1" role="dialog" aria-labelledby="slikModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="slikModalLabel">Register SLIK Details</h5>
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
        function setCookieAndRedirect(type) {
            // Set a cookie with a 1-minute expiration
            var expirationTime = new Date(Date.now() + 6000000); // 60000 milliseconds = 1 minute
            document.cookie = "filter=" + type + ";expires=" + expirationTime.toUTCString() + ";path=/;samesite=strict";

            window.location = '{{ route('slik.create') }}';
        }


        $(document).ready(function() {
            var table = $('#table-device').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude the 4th column (Aksi)
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude the 4th column (Aksi)
                        }
                    },

                ],
                searching: true,
                paging: true,
                'ordering': false,
                "pageLength": 30,
            });
        });
    </script>

    <script>
        function exportSelectedRows() {
            var selectedRows = [];
            var isChecked = false;
            // Loop through each checkbox in the table
            $('#table-device tbody input[type="checkbox"]:checked').each(function() {
                // Get the corresponding row data
                var row = $(this).closest('tr');
                var noRef = row.find('td:eq(1)').text(); // Assuming no_ref is in the second column
                var tujuanPermintaan = row.find('td:eq(7)')
                    .text(); // Assuming tujuan_permintaan is in the sixth column
                var nik = row.find('td:eq(3)').text(); // Assuming nik is in the fourth column

                // Concatenate the data and push it to the array
                var rowData = `${noRef}|0${tujuanPermintaan}|I|${nik}`;
                selectedRows.push(rowData);


                isChecked = true;
            });


            if (!isChecked) {
                // Show SweetAlert alert if no rows are selected
                Swal.fire({
                    type: 'warning',
                    title: 'Oops...',
                    text: 'Pilih salah satu baris!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            } else {
                var currentDate = new Date();
                var formattedDateTime = currentDate.toISOString().slice(0, 19).replace("T", "_").replace(/\s/g, '_')
                    .replace(
                        /:/g, '-'); // Format: YYYY-MM-DD_HH-mm-ss
                // Create a Blob containing the text data
                var blob = new Blob([selectedRows.join('\n')], {
                    type: 'text/plain'
                });

                // Create a download link and trigger the download
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'exported_data_' + formattedDateTime + '.txt';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        function deleteItem(itemId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm-' + itemId).submit();
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            // Triggered when the modal is about to be shown
            $('#slikModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var regisId = button.data('regis-id');

                // Make an AJAX request to get memo details
                $.ajax({
                    type: 'GET',
                    url: '/slik/' + regisId,
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
                                    html += '<tr><td width="25%">' + key.replace(/_/g, ' ')
                                        .toUpperCase() +
                                        '</td><td>:</td><td>' + debitur[key].toUpperCase() +
                                        '</td></tr>';
                                }
                            }
                            html += '</table>';
                            $('#slikModal .modal-body').html(html);



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
