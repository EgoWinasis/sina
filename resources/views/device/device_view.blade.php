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
                        <li class="breadcrumb-item active">Device</li>
                    </ol>

                    <div class="row pb-5 d-flex justify-content-center">
                        <div class="col-xl-10">
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
                                                <th>Devices</th>
                                                <th>User</th>
                                                <th>Pemilik</th>
                                                <th>Kantor</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($devices as $device)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $device->device }}</td>
                                                    <td class="name">{{ $device->user }}</td>
                                                    <td>{{ $device->pemilik }}</td>
                                                    <td>{{ $device->kantor }}</td>
                                                    <td>
                                                        <a class="btn btn-info m-2">Show</a>
                                                        <a class="btn btn-primary m-2"
                                                            href="{{ route('device.edit', $device->id) }}">Edit</a>
                                                        <a class="btn btn-danger m-2 btn-delete">Delete</a>
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
@section('plugins.sweetalert2', true)
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
                            columns: [0, 1, 2, 3,4] // Exclude the 4th column (Aksi)
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 3,4] // Exclude the 4th column (Aksi)
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: [0, 1, 2, 3,4] // Exclude the 4th column (Aksi)
                        }
                    },
                ],
                searching: true,
                paging: true,
            });
        });

        // delete button

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var nama = $(this).parent().parent().find('.nama').text();
            var token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Hapus data user ' + nama + ' ?',
                text: "Semua data user akan hilang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "/device/" + id,
                        data: {
                            'id': id,
                            '_token': token,
                        },
                        success: function(data) {
                            Swal.fire(
                                'Deleted!',
                                'Data user ' + nama + ' berhasil dihapus!',
                                'success'
                            )
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error
                            })
                        }
                    });

                }
            })

        });
    </script>
@endsection
