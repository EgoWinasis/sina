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
                        <li class="breadcrumb-item active">Sosial Media</li>
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
                                                <th>Platform</th>
                                                <th>Username dan Password</th>
                                                <th>Kantor</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($sosial_media as $sosmed)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $sosmed->platform }}</td>
                                                    <td class="text-center">
                                                        <i class="fas fa-eye fa-lg cursor-pointer"
                                                            data-id="{{ $sosmed->id }}" data-toggle="tooltip"
                                                            title="View"></i>
                                                    </td>
                                                    <td>{{ $sosmed->kantor }}</td>
                                                    <td class="text-center">
                                                        <a class="btn btn-primary m-2" href="{{ route('sosmed.edit', $sosmed->id) }}">Edit</a>
                                                       
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
            </main>
            @include('footer')
        </div>
    </div>

@stop



@section('css')
    <link
        href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.css"
        rel="stylesheet">
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.js">
    </script>

    <script>
        function add() {
            window.location = "{{ route('sosmed.create') }}";
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

        $(document).ready(function() {
            $('.cursor-pointer').click(function() {
                const itemId = $(this).data('id'); // Get the dynamic ID from the clicked button

                Swal.fire({
                    title: 'Master Password',
                    input: 'text',
                    inputPlaceholder: 'Master Password',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    showLoaderOnConfirm: true,
                    preConfirm: (password) => {
                        // Generate the expected master password based on the current date (e.g., IT25032024 for 25 March 2024)
                        const currentDate = new Date();
                        const day = currentDate.getDate().toString().padStart(2, '0');
                        const month = (currentDate.getMonth() + 1).toString().padStart(2,
                            '0'); // Months are zero-based
                        const year = currentDate.getFullYear().toString();
                        const expectedPassword = 'IT' + day + month + year;
                        // console.log(expectedPassword);
                        // Check if the entered password is equal to the expected password
                        if (password === expectedPassword) {
                            return true; // Resolve the promise if the password is correct
                        } else {
                            Swal.showValidationMessage(
                                'Invalid password'
                            ); // Show a validation message if the password is incorrect
                            return false; // Reject the promise if the password is incorrect
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/sosmed/' +
                                itemId, // Adjust the URL to match your Laravel route
                            type: 'GET',
                            success: function(response) {
                                const {
                                    username,
                                    password,
                                    platform
                                } = response.data[0];
                                // Construct HTML table
                                const tableHtml = `
                            <table class="table">
                                <tr><td>Platform</td><td>:</td><td>${platform}</td></tr>
                                <tr><td>Username</td><td>:</td><td>${username}</td></tr>
                                <tr><td>Password</td><td>:</td><td>${password}</td></tr>
                            </table>`;

                                // Show table in SweetAlert modal
                                Swal.fire({
                                    title: 'Data Sosial Media',
                                    html: tableHtml,
                                    icon: 'success'
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error', 'Failed to retrieve data', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
