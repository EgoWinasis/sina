@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - Kelola Akun')


@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Kelola Akun</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Kelola Akun</li>
                    </ol>

                    <div class="row pb-5 d-flex justify-content-center">
                        <div class="col-xl-12">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif

                            <div class="row my-3">
                                <div class="col-md-12">
                                    <x-adminlte-button onclick="return add();" label="Buat Akun" theme="primary"
                                        icon="fas fa-plus" />
                                </div>
                            </div>

                            <div class="card mb-4">

                                <div class="card-body">
                                    <div class="table-responsive">

                                        <table id="table-device" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Jabatan</th>
                                                    <th>Kantor</th>
                                                    <th>Status Akun</th>
                                                    @if (auth()->check() && auth()->user()->role == 'super')
                                                        <th>Role</th>
                                                    @endif
                                                    <th>Foto</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->jabatan }}</td>
                                                        <td>{{ $user->kantor }}</td>
                                                        {{-- status --}}
                                                        <td class="text-center">
                                                            @if ($user->isActive == 1)
                                                                <span class="badge badge-success p-2">Active</span>
                                                            @else
                                                                <span class="badge badge-danger p-2">Inactive</span>
                                                            @endif
                                                        </td>
                                                        {{-- role akun --}}
                                                        @if (auth()->check() && auth()->user()->role == 'super')
                                                            <td class="text-center">
                                                                @if ($user->role == 'super')
                                                                    <span class="badge badge-primary p-2">
                                                                        <i class="fas fa-star"></i>
                                                                        <i class="fas fa-star"></i>
                                                                        <i class="fas fa-star"></i><br>
                                                                        Super
                                                                    </span>
                                                                @elseif($user->role == 'admin')
                                                                    <span class="badge badge-success p-2">
                                                                        <i class="fas fa-star"></i>
                                                                        <i class="fas fa-star"></i><br>
                                                                        Admin
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-danger p-2">
                                                                        <i class="fas fa-star"></i><br>
                                                                        User
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        {{-- gambar --}}
                                                        <td class="text-center">
                                                            <img src="{{ asset('storage/profiles/' . $user->image) }}"
                                                                alt="{{ $user->name }} Image" style="max-width: 100px;">
                                                        </td>
                                                        {{-- aksi --}}
                                                        <td class="text-center">
                                                            <button data-user-id="{{ $user->id }}"
                                                                class="btn btn-info btn-show m-2">Show</button>
                                                            {{-- aktivasi --}}
                                                            @if ($user->isActive == 1)
                                                                <button data-user-id="{{ $user->id }}" data-type="0"
                                                                    class="btn btn-danger m-2 btn-disable">Disable</button>
                                                            @else
                                                                <button data-user-id="{{ $user->id }}" data-type="1"
                                                                    class="btn btn-success m-2 btn-disable">Enable</button>
                                                            @endif
                                                            {{-- change role --}}
                                                            @if (auth()->check() && auth()->user()->role == 'super')
                                                                <button data-user-id="{{ $user->id }}" data-type="role"
                                                                    class="btn btn-primary m-2 btn-role">Role</button>
                                                                @if ($user->jabatan == 'Staf Kredit')
                                                                    <button data-user-id="{{ $user->id }}"
                                                                        data-type="ao"
                                                                        class="btn btn-warning m-2 btn-ao">AO</button>
                                                                @endif
                                                            @endif

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
        // add
        function add() {
            window.location = "{{ route('pengguna.create') }}";
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

                ],
                searching: true,
                paging: true,
                'ordering': false,
                "pageLength": 30,
            });
        });
    </script>

    <script>
        // disable user
        $(document).ready(function() {
            $('.btn-disable').on('click', function() {
                // Get the user ID from the data attribute
                var userId = $(this).data('user-id');
                var type = $(this).data('type');
                var text = 'Disable Account User';
                if (type == 1) {
                    text = 'Enable Account User'
                }
                // Show SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: text,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    // If user clicks "Yes"
                    if (result.value == true) {
                        // Make an Ajax request to update the isActive column
                        $.ajax({
                            type: 'PUT',
                            url: '/pengguna/' + userId, // Adjust the route URL
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'type': type,
                            },
                            success: function(data) {
                                // Handle success
                                Swal.fire({
                                    type: 'success',
                                    title: 'Change Status User',
                                    text: data.message,
                                }).then((result) => {
                                    location.reload();
                                });
                            },
                            error: function(error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error,
                                });
                            }
                        });
                    }
                });
            });
        });

        // change role
        $(document).ready(function() {
            $('.btn-role').on('click', function() {
                // Get the user ID and type from the data attributes
                var userId = $(this).data('user-id');
                var type = $(this).data('type');

                // Show SweetAlert confirmation dialog with a select dropdown
                Swal.fire({
                    title: 'Change User Role',
                    html: '<label for="role">Select a role:</label>' +
                        '<select id="role" class="swal2-input">' +
                        '<option value="super">Super</option>' +
                        '<option value="admin">Admin</option>' +
                        '<option value="user">User</option>' +
                        '</select>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Change Role'
                }).then((result) => {
                    // If user clicks "Change Role"
                    if (result.value) {
                        // Get the selected role from the dropdown
                        var selectedRole = $('#role').val();

                        // Make an Ajax request to update the user's role
                        $.ajax({
                            type: 'PUT',
                            url: '/pengguna/' + userId, // Adjust the route URL
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'type': type,
                                'selectedRole': selectedRole,
                            },
                            success: function(data) {
                                // Handle success
                                Swal.fire({
                                    type: 'success',
                                    title: 'Change Role',
                                    text: data.message,
                                }).then((result) => {
                                    location.reload();
                                });
                            },
                            error: function(error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error,
                                });
                            }
                        });
                    }
                });
            });
        });

        // store data
        var dataAo = {!! json_encode($dataAo) !!};

        // change kode ao
        $(document).ready(function() {
            $('.btn-ao').on('click', function() {
                // Get the user ID and type from the data attributes
                var userId = $(this).data('user-id');
                var type = $(this).data('type');

                var selectOptions = '';
                for (var i = 0; i < dataAo.length; i++) {
                    selectOptions += '<option value="' + dataAo[i].kode_ao + '">' + dataAo[i].kode_ao +
                        ' - ' + dataAo[i].nama + '</option>';
                }
                // Show SweetAlert confirmation dialog with a select dropdown
                Swal.fire({
                    title: 'Change Kode AO',
                    html: '<label for="ao">Select Kode AO:</label>' +
                        '<select id="ao" class="swal2-input">' +
                        selectOptions +
                        '</select>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Change'
                }).then((result) => {
                    // If user clicks "Change AO"
                    if (result.value) {
                        // Get the selected role from the dropdown
                        var selectedAo = $('#ao').val();

                        // Make an Ajax request to update the user's role
                        $.ajax({
                            type: 'PUT',
                            url: '/pengguna/' + userId, // Adjust the route URL
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'type': type,
                                'selectedAo': selectedAo,
                            },
                            success: function(data) {
                                // Handle success
                                Swal.fire({
                                    type: 'success',
                                    title: 'Change Kode AO',
                                    text: data.message,
                                }).then((result) => {
                                    location.reload();
                                });
                            },
                            error: function(error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error,
                                });
                            }
                        });
                    }
                });
            });
        });


        // show 
        $(document).ready(function() {
            $('.btn-show').click(function() {
                // Get the user ID from the data attribute
                var userId = $(this).data('user-id');

                // Make an Ajax request to fetch user data

                $.ajax({
                    type: 'GET',
                    url: '/pengguna/' + userId, // Replace with your actual route
                    success: function(data) {

                        let tableHTML = `
                                            <table class="table table-borderless" >
                                                <tr>
                                                    <td colspan='3'><img src="/storage/profiles/${data.image}" alt="User Image" class="img-fluid" width='200px'></td>
                                                </tr>
                                                <tr>
                                                    <th class='text-left'>Email</th>
                                                    <td class='text-left'>:</td>
                                                    <td class='text-left'>${data.email}</td>
                                                </tr>
                                                <tr>
                                                    <th  class='text-left'>Name</th>
                                                    <td  class='text-left'>:</td>\
                                                    <td  class='text-left'>${data.name}</td>
                                                </tr>
                                                <tr>
                                                    <th class='text-left'>Jabatan</th>
                                                    <td class='text-left'>:</td>
                                                    <td class='text-left'>${data.jabatan}</td>
                                                </tr>
                                                <tr>
                                                    <th class='text-left'>Kantor</th>
                                                    <td class='text-left'>:</td>
                                                    <td class='text-left'>${data.kantor}</td>
                                                </tr>
                                            
                                           
                                            `;
                        if (data.kode_ao) {
                            tableHTML += `
                        <tr>
                            <th class='text-left'>Kode AO</th>
                            <td class='text-left'>:</td>
                            <td class='text-left'>${data.kode_ao}</td>
                        </tr>
                    `;
                        }

                        tableHTML += `</table>`;
                        // Handle success, e.g., display data using SweetAlert modal
                        Swal.fire({
                            title: 'User Details',
                            html: tableHTML,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Close'
                        });
                    },
                    error: function(error) {
                        // Handle error, e.g., show an error message
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch user data.',
                        });
                    }
                });
            });
        });
    </script>

@endsection
