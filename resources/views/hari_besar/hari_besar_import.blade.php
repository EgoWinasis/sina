@extends('adminlte::page')

@section('title', 'BPR Nusamba Adiwerna - Hari Besar')

@section('content')
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Hari Besar</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item">Desain</li>
                        <li class="breadcrumb-item">Hari Besar</li>
                        <li class="breadcrumb-item active">Import</li>
                    </ol>

                    <div class="row pb-5 d-flex justify-content-center">
                        <div class="col-xl-10">

                            <div class="card mb-4">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-primary" type="button">Import</button>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile"
                                                        accept=".xlsx, .xls" name="excel_file">
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="downloadExcelIcon"
                                                        style="cursor: pointer;" title="Download Template Excel">
                                                        <i class="fas fa-file-excel"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <table id="excelDataTable" class="table">
                                                <thead>
                                                    <tr id="tableHeaders"></tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="col-xl-12 text-center">
                                            <button id="submitBtn" class="btn btn-primary">Submit</button>
                                        </div>
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


@section('plugins.Select2', true)

@section('css')
    <link
        href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/sp-2.2.0/datatables.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <script>
        $(".tanggal").flatpickr();
        // 


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


        var tableHeaders = [];

        // Function to display data in the table
        function displayExcelData(data) {


            // check
            var hasHariColumn = data[0].includes('Hari');
            var hasTanggalColumn = data[0].includes('Tanggal');


            if (!hasHariColumn || !hasTanggalColumn) {
                // Show SweetAlert error message
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: 'file excel salah',
                });
                return; // Exit function if columns are missing
            }


            var table = $('#excelDataTable');
            table.find('tbody').empty();


            // Display headers

            tableHeaders = data[0];
            var headersRow = $('<tr>');
            $.each(tableHeaders, function(index, value) {
                headersRow.append($('<th>').text(value));
            });
            $('#tableHeaders').html(headersRow);

            // Display rows
            for (var i = 1; i < data.length; i++) {
                var rowData = data[i];
                var row = $('<tr>');
                $.each(rowData, function(index, value) {
                    row.append($('<td>').text(value));
                });
                table.append(row);
            }
        }


        $(document).ready(function() {
            // Handle file selection and display data
            $('#customFile').change(function() {
                var file = $(this)[0].files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var data = new Uint8Array(e.target.result);
                        var workbook = XLSX.read(data, {
                            type: 'array'
                        });
                        var sheetName = workbook.SheetNames[0];
                        var sheet = workbook.Sheets[sheetName];
                        var excelData = XLSX.utils.sheet_to_json(sheet, {
                            header: 1
                        });
                        displayExcelData(excelData);
                    };
                    reader.readAsArrayBuffer(file);
                }
            });

            // Handle form submission
            $('#submitBtn').click(function() {
                var tableData = [];
                $('#excelDataTable tbody tr').each(function() {
                    var rowData = {};
                    $(this).find('td').each(function(index) {
                        rowData[tableHeaders[index]] = $(this).text();
                    });
                    tableData.push(rowData);
                });

                if (tableData.length === 0) {
                    // Show SweetAlert error message if tableData is empty
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'No data to submit. Please import data from an Excel file first.',
                    });
                    return; // Exit the function
                }

                // console.log(tableData);
                // Send data to server
                $.ajax({
                    url: '{{ route('storeExcelHari') }}', // Use the route name to generate the URL
                    type: 'POST',
                    data: {
                        data: tableData
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for Laravel security
                    },
                    success: function(response) {
                        // Show success message with SweetAlert
                        Swal.fire({
                            type: 'success',
                            title: 'Success',
                            text: 'Data stored successfully'
                        }).then(() => {
                            window.location.href = "{{ route('hari-besar.index') }}";
                        });
                    },
                    error: function(xhr, status, error) {
                        // Show error message with SweetAlert
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: 'Failed to save data: ' + error
                        });
                    }
                });


            });



        });



        document.getElementById('downloadExcelIcon').addEventListener('click', function() {
            // Create a download link
            var link = document.createElement('a');
            link.href = "{{ asset('storage/hari_besar/hari.xlsx') }}"; // Path to your Excel template file
            link.download = 'hari.xlsx'; // Specify the desired file name

            // Simulate a click on the link
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
@endsection
