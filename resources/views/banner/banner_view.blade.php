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
                        <li class="breadcrumb-item active">Banner</li>
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
                                                <th>Tanggal Permintaan</th>
                                                <th>Kantor</th>
                                                <th>Toko</th>
                                                <th>Alamat</th>
                                                <th>Deadline</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($banners as $banner)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $banner->created_at }}</td>
                                                    <td class="name">{{ $banner->kantor }}</td>
                                                    <td>{{ $banner->toko }}</td>
                                                    <td>{{ $banner->alamat }}</td>
                                                    <td>{{ $banner->deadline }}</td>
                                                    <td>
                                                        @php
                                                            $statusClass = '';
                                                            switch ($banner->status) {
                                                                case 'PENDING':
                                                                    $statusClass = 'badge-warning';
                                                                    break;
                                                                case 'SELESAI':
                                                                    $statusClass = 'badge-success';
                                                                    break;
                                                                case 'BATAL':
                                                                    $statusClass = 'badge-danger';
                                                                    break;
                                                                default:
                                                                    $statusClass = 'badge-secondary';
                                                                    break;
                                                            }
                                                        @endphp
                                                        <span class="badge {{ $statusClass }}">{{ $banner->status }}</span>
                                                    </td>

                                                    <td>
                                                        <a class="btn btn-info m-2" data-toggle="modal"
                                                            data-target="#bannerModal" data-banner-id="{{ $banner->id }}">
                                                            Show</a>
                                                        <a class="btn btn-primary m-2"
                                                            href="{{ route('banner.edit', $banner->id) }}">Edit</a>
                                                        @if (Auth::user()->role == 'super')
                                                            <a class="btn btn-warning m-2"
                                                                data-banner-id="{{ $banner->id }}"
                                                                id="statusBtn">Status</a>
                                                            <a class="btn btn-secondary m-2"
                                                                data-banner-id="{{ $banner->id }}"
                                                                id="desainBtn">Desain</a>
                                                        @endif
                                                        <a class="btn btn-danger m-2 btn-delete"
                                                            data-id="{{ $banner->id }}">Delete</a>
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

                <!-- Modal -->
                <div class="modal fade" id="bannerModal" tabindex="-1" role="dialog" aria-labelledby="slikModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="slikModalLabel">Permintaan Banner Details</h5>
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
@section('plugins.sweetalert2', true)
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.js">
    </script>

    <script>
        function add() {
            window.location = "{{ route('banner.create') }}";
        }
        $(document).ready(function() {
            var table = $('#table-device').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4,5,6] // Exclude the 4th column (Aksi)
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4,5,6] // Exclude the 4th column (Aksi)
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
            var token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Peringatan',
                text: "Hapus Data ?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "/banner/" + id,
                        data: {
                            'id': id,
                            '_token': token,
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error
                            });
                        }
                    });

                }
            })

        });


        $(document).ready(function() {
            // Triggered when the modal is about to be shown
            $('#bannerModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var bannerId = button.data('banner-id');

                // Make an AJAX request to get memo details
                $.ajax({
                    type: 'GET',
                    url: '/banner/' + bannerId,
                    dataType: 'json',
                    contentType: 'application/json; charset=utf-8',
                    success: function(response) {
                        // Update the modal content with the data received from the server
                        if (response && response.data) {
                            var banner = response.data[0];

                            // Update the modal content with the received data for the debtor
                            var html = '<table class="table" width="100%">';
                            for (var key in banner) {
                                if (key !== 'id') {
                                    var value = banner[key];
                                    if (key === 'panjang' || key === 'lebar') {
                                        value +=
                                            ' cm'; // Append 'cm' after panjang and lebar values
                                    }

                                    html += '<tr><td width="25%">' + key.replace(/_/g, ' ')
                                        .toUpperCase() +
                                        '</td><td>:</td>';
                                    if (key === 'image' || key === 'desain') {
                                        if (value !== '-') {
                                            // Prepend the src attribute with the path to your assets
                                            value = 'storage/banner/' + value;
                                            html += '<td><a href="' + value +
                                                '" target="_blank" title="Lihat" style="cursor: zoom-in;"><img src="' +
                                                value + '" width="100"></a></td>';
                                        } else {
                                            html += '<td>No image</td>';
                                        }
                                    } else {
                                        html += '<td>' + value + '</td>';
                                    }
                                    html += '</tr>';
                                }
                            }
                            html += '</table>';
                            $('#bannerModal .modal-body').html(html);



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

        // Attach click event to the button
        document.getElementById('statusBtn').addEventListener('click', function(event) {
            // Get the banner id from the data attribute
            const bannerId = this.getAttribute('data-banner-id');
            Swal.fire({
                title: 'Select Status',
                input: 'select',
                inputOptions: {
                    'Pending': 'Pending',
                    'Selesai': 'Selesai',
                    'Batal': 'Batal'
                },
                inputPlaceholder: 'Select a status',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to select something!'
                    }
                }
            }).then((result) => {
                // Check if the user clicked OK
                if (result.value) {
                    // Send AJAX request
                    const selectedStatus = result.value;
                    // Replace '/update-status' with your actual endpoint
                    fetch(`/banner/${bannerId}`, {
                        method: 'PUT',
                        body: JSON.stringify({
                            status: selectedStatus
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => {
                        if (response.ok) {
                            // Display success message
                            Swal.fire({
                                type: 'success',
                                title: 'Success',
                                text: 'Status updated successfully',
                            }).then(() => {
                                // Reload the page
                                window.location.reload();
                            });
                        } else {
                            // Handle error response
                            console.error('Failed to update status');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        });


        document.getElementById('desainBtn').addEventListener('click', function(event) {
            // Get the banner id from the data attribute
            const bannerId = this.getAttribute('data-banner-id');
            Swal.fire({
                title: 'Upload Image',
                input: 'file',
                inputAttributes: {
                    accept: 'image/*',
                    'aria-label': 'Upload your banner image',
                    onchange: 'previewImage(event)'
                },
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Upload',
                inputValidator: (file) => {
                    if (!file) {
                        return 'You need to select an image file!';
                    }
                }
            }).then((file) => {
                if (file.value) {
                    const formData = new FormData();
                    formData.append('id', bannerId);
                    formData.append('imageDesain', file.value);
                    fetch(`/banner/${bannerId}/upload-image`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    }).then(response => {
                        if (response.ok) {
                            // Display success message
                            Swal.fire({
                                type: 'success',
                                title: 'Success',
                                text: 'Image uploaded successfully',
                            }).then(() => {
                                // Reload the page
                                window.location.reload();
                            });
                        } else {
                            response.text().then(errorMessage => {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Error',
                                    text: errorMessage || 'Failed to upload image',
                                });
                            });
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        });

        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                Swal.update({
                    imageUrl: e.target.result,
                    imageAlt: 'Selected image',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Upload'
                });
            };

            reader.readAsDataURL(file);
        }
    </script>
@endsection
