@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                @if (auth()->check())
                    @if (auth()->user()->jabatan == '-' && auth()->user()->image == 'user_default.png' && auth()->user()->kantor == '-')
                        @include('dashboard/dashboard_awal')
                    @elseif (auth()->user()->role == 'super')
                        @include('dashboard/dashboard_super')
                    @elseif (auth()->user()->role == 'user' && auth()->user()->jabatan == 'Staf Kredit')
                        @include('dashboard/dashboard_kredit')
                    @elseif (auth()->user()->role == 'user' && auth()->user()->jabatan == 'Kepala Kantor Kas')
                        @include('dashboard/dashboard_kepala')
                    @elseif (auth()->user()->role == 'user' &&
                            (auth()->user()->jabatan == 'KKPO' ||
                                auth()->user()->jabatan == 'Kepala Kantor Cabang' ||
                                auth()->user()->jabatan == 'Direktur' ||
                                auth()->user()->jabatan == 'Direktur Utama' ||
                                auth()->user()->jabatan == 'Komisaris'))
                        @include('dashboard/dashboard_direksi')
                    @else
                        @include('dashboard/dashboard_super')
                    @endif
                @endif

            </main>
            @include('footer')
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">

@endsection

@section('js')
    <script src="{{ asset('dist/js/formatted-numbers.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#table-droping').DataTable({
                "columns": [
                    null, // Assuming 'kode_ao' column
                    null, // Assuming 'nama_ao' column
                    {
                        "render": $.fn.dataTable.render.number('.', '.', 0, '',
                            '') // Adjust based on the actual position of the 'sum_plafon' column
                    }
                    // Add other columns as needed
                ],
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

                "order": [
                    [2, "desc"] // Assuming you want to order by the second column in descending order
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 1, 3, 4, 5, 6, 7, 8],
                }],

                searching: true,
                paging: true,
                'ordering': true,
                "pageLength": 30,
            });
        });


        $(document).ready(function() {
            $('#modalAoBaru').click(function() {
                Swal.fire({
                    title: 'Droping AO Baru',
                    html: '<select id="selectOption" class="form-control">' +
                        '<option value="rc">Rico</option>' +
                        '</select>' +
                        '<input id="inputDroping" class="swal2-input">',
                    showCancelButton: true,
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    focusConfirm: false,
                    preConfirm: () => {
                        const kode_ao = $('#selectOption').val();
                        const nama_ao = $('#selectOption :selected').text();
                        const dropingString = $('#inputDroping').val();

                        // Convert the value to an integer
                        const droping = parseInt(dropingString, 10);
                        // Get the CSRF token from the meta tag
                        const csrfToken = $('meta[name="csrf-token"]').attr('content');
                        console.log(droping);
                        $.ajax({
                            url: '/aobaru',
                            method: 'POST',
                            data: {
                                kode_ao: kode_ao,
                                nama_ao: nama_ao,
                                droping: droping,
                                _token: csrfToken,
                            },
                            success: function(response) {
                                // Handle the success response
                                Swal.fire('Data saved successfully!', '', 'success')
                                    .then(() => {
                                        location.reload();
                                    });
                            },
                            error: function(error) {
                                // Check if the error response contains a custom error message
                                let errorMessage = 'Error saving data.';

                                if (error.responseJSON && error.responseJSON
                                    .message) {
                                    errorMessage = error.responseJSON.message;
                                }

                                Swal.fire(errorMessage, '', 'error');
                            }
                        });

                        // For now, let's just show an alert for testing
                        Swal.fire('Data saved successfully!', '', 'success');
                    }
                });
            });
        });

        $('#modalPencapaian').click(function() {
            // Get the current date
            var currentDate = new Date();
            // Get the previous month
            currentDate.setMonth(currentDate.getMonth() - 1);
            // Format the previous month as "MMMM YYYY" (e.g., "October 2023")
            var formattedPreviousMonth = new Intl.DateTimeFormat('en-US', {
                month: 'long',
                year: 'numeric'
            }).format(currentDate);

            var aoOptions = {
                'BR': 'PRAYUDIA BRAMONO',
                'TN': 'TIONO',
                'MS': 'MEKA SEPRIANDOKO',
                'MA': 'MUHAMAD ALVARIS',
                'LG': 'LUKMAN GIANTORO',
                'RB': 'RUBY SETIYAWAN',
                'AY': 'ANGGIT PUJI WAHYUDI',
                'TE': 'TOTO EKO SAPUTRA JAYA',
                'BN': 'BENI SETIAWAN',
                'RK': 'RAHDIAN KWATRA H',
                'DT': 'DUTA ANDIKA',
                'ML': 'KREDIT KARYAWAN',
                'DLL': 'N/A'
            };
            var bulanOptions = {
                '1': 'JANUARI',
                '2': 'FEBRUARI',
                '3': 'MARET',
                '4': 'APRIL',
                '5': 'MEI',
                '6': 'JUNI',
                '7': 'JULI',
                '8': 'AGUSTUS',
                '9': 'SEPTEMBER',
                '10': 'OKTOBER',
                '11': 'NOVEMBER',
                '12': 'DESEMBER'
            };



            var selectOptionsHTML = '<select id="selectOptionMonth" class="form-control ">';
            for (var key in bulanOptions) {
                if (bulanOptions.hasOwnProperty(key)) {
                    selectOptionsHTML += '<option value="' + key + '">' + bulanOptions[key] + '</option>';
                }
            }
            // Close the select tag
            selectOptionsHTML += '</select>';
            // Build the HTML for the select options
            selectOptionsHTML += '<select id="selectOption" class="form-control mt-3">';
            // Loop through the object and create an option for each key-value pair
            for (var key in aoOptions) {
                if (aoOptions.hasOwnProperty(key)) {
                    selectOptionsHTML += '<option value="' + key + '">' + aoOptions[key] + '</option>';
                }
            }
            selectOptionsHTML += '</select>';

            // Add the input field
            selectOptionsHTML += '<input id="inputNsb" placeholder="Nasabah" required class="swal2-input">';
            selectOptionsHTML += '<input id="inputDroping" placeholder="OS" required class="swal2-input">';
            Swal.fire({
                title: 'Set Pencapaian Bulan Lalu',
                html: selectOptionsHTML,
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                preConfirm: () => {
                    const kode_ao = $('#selectOption').val();
                    const nama_ao = $('#selectOption :selected').text();
                    const bulan = $('#selectOptionMonth :selected').val();
                    const dropingNsb = $('#inputNsb').val();
                    const dropingOs = $('#inputDroping').val();

                    // Convert the value to an integer
                    const bln = parseInt(bulan, 10);
                    const os = parseInt(dropingOs, 10);
                    const nsb = parseInt(dropingNsb, 10);
                    // Get the CSRF token from the meta tag
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '/pencapaian',
                        method: 'POST',
                        data: {
                            kode_ao: kode_ao,
                            nama_ao: nama_ao,
                            bln: bln,
                            nsb: nsb,
                            os: os,
                            _token: csrfToken,
                        },
                        success: function(response) {
                            // Handle the success response
                            Swal.fire('Data saved successfully!', '', 'success')
                                .then(() => {
                                    location.reload();
                                });
                        },
                        error: function(error) {
                            // Check if the error response contains a custom error message
                            let errorMessage = 'Error saving data.';

                            if (error.responseJSON && error.responseJSON
                                .message) {
                                errorMessage = error.responseJSON.message;
                            }

                            Swal.fire(errorMessage, '', 'error');
                        }
                    });

                    // For now, let's just show an alert for testing
                    Swal.fire('Data saved successfully!', '', 'success');
                }
            });
        });
    </script>


@stop
