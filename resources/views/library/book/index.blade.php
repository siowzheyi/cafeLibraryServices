<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>CAFÉ LIBRARY SERVICES</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px;
            }
    
            table, th, td {
                border: 1px solid black;
            }
    
            th, td {
                padding: 10px;
                text-align: left;
            }
    
            .pagination {
                display: flex;
                justify-content: center;
                margin-top: 20px;
            }
    
            .pagination button {
                padding: 10px;
                margin: 0 5px;
                cursor: pointer;
            }
             #movieList {
            list-style: none;
            padding: 0;
        }

        .movieItem {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark bgtopbar">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">CAFÉ LIBRARY</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
           
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item">Library Staff</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="{{ route('login') }}">>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark bgsidebar" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">MAIN</div>
                            <a class="nav-link" href="{{route('dashboard')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">PAGES</div>
                           
                            <a class="nav-link collapsed active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsLib" aria-expanded="false" aria-controls="collapseLayoutsLib">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Library
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse bgsubmenu show" id="collapseLayoutsLib" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('table.index') }}">Table</a>
                                    <a class="nav-link active" href="{{ route('book.index') }}">Book</a>
                                    <a class="nav-link " href="{{ route('room.index') }}">Room</a>
                                    <a class="nav-link" href="{{ route('equipment.index') }}">Equipment</a>
                                    <a class="nav-link " href="{{ route('announcement.index') }}">Announcement</a>

                                </nav>
                            </div> 
                            <a class="nav-link" href="{{route('booking.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Booking 
                            </a>
                            <a class="nav-link" href="{{route('report.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Reported List
                            </a>
                            <a class="nav-link" href="{{route('report.index')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Penalty Report
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Library Book</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Library Book</li>
                        </ol>
                        @if (Session::has('success'))
                            <div class="alert alert-success" id="flash-message">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" id="flash-message">{{ $error }}</div>
                    @endforeach

                        <div class="row mb-2">
                            <div class="col-12">
                                <a href="{{ route('book.create') }}" class="btn btn-success float-end"><i class="fa fa-plus"></i> Create New Book</a>
                                
                                <a href="#" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#modelId"><i class="fa fa-plus"></i> Import New Book</a>
                            </div>
                        </div>

                       
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                List of Library Book
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" >
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book Name</th>
                                            <th>Genre</th>
                                            <th>Price</th>
                                            <th>Availability</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                              <!-- import dorm modal -->
                            <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Import Book</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('importbook') }}" method="post" enctype="multipart/form-data">
                                            <div class="modal-body">

                                                {{ csrf_field() }}
                                                
                                                <div class="form-group">
                                                    <input type="file" name="excel" required>
                                                </div>
                                                <input type="text" name="library_id" id="library_id"  hidden>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Import</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- <form id="fileUploadForm" enctype="multipart/form-data" href="{{ route('book.index') }}">
                                <input type="file" id="fileInput" accept=".xls, .xlsx, .csv, .txt" />
                                <button type="button" onclick="handleFile()">Upload and View</button>
                            </form>
                        
                            <div id="fileDataContainer">
                                <!-- File data will be displayed here -->
                                <table id="dataTable"></table>
                                <div class="pagination" id="pagination"></div>
                            </div> --}}
{{--                         
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
                            <script>
                                var currentData = [];
                                var currentPage = 1;
                                var itemsPerPage = 20;
                        
                                function handleFile() {
                                    var fileInput = document.getElementById('fileInput');
                                    var fileDataContainer = document.getElementById('fileDataContainer');
                        
                                    var file = fileInput.files[0];
                        
                                    if (file) {
                                        var reader = new FileReader();
                        
                                        reader.onload = function (e) {
                                            var data = e.target.result;
                                            var workbook = XLSX.read(data, { type: 'binary' });
                                            var sheetName = workbook.SheetNames[0];
                                            var worksheet = workbook.Sheets[sheetName];
                        
                                            currentData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                                            displayData(currentPage);
                                        };
                        
                                        reader.readAsBinaryString(file);
                                    } else {
                                        alert('Please select an Excel file.');
                                    }
                                }
                        
                                function displayData(page) {
                                    var startIndex = (page - 1) * itemsPerPage;
                                    var endIndex = startIndex + itemsPerPage;
                        
                                    var table = '<tr><th>No</th><th>ISBN</th><th>Title</th><th>Author</th><th>Genre</th><th>Publisher</th><th>Price(RM)</th></tr>'; // Adjust column headers
                        
                                    for (var i = startIndex; i < endIndex && i < currentData.length; i++) {
                                        var row = currentData[i];
                                        table += '<tr>';
                                        for (var j = 0; j < 7; j++) { // Displaying the first three columns, adjust as needed
                                            table += '<td>' + (row[j] || '') + '</td>';
                                        }
                                        table += '</tr>';
                                    }
                        
                                    document.getElementById('dataTable').innerHTML = table;
                        
                                    // Display pagination buttons
                                    displayPagination();
                                }
                        
                                function displayPagination() {
                                    var totalPages = Math.ceil(currentData.length / itemsPerPage);
                                    var paginationContainer = document.getElementById('pagination');
                                    var paginationButtons = '';
                        
                                    for (var i = 1; i <= totalPages; i++) {
                                        paginationButtons += '<button onclick="changePage(' + i + ')">' + i + '</button>';
                                    }
                        
                                    paginationContainer.innerHTML = paginationButtons;
                                }
                        
                                function changePage(page) {
                                    currentPage = page;
                                    displayData(currentPage);
                                }
                            </script> --}}
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; CLSS 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    </body>
    <script>
        $(document).ready(function() {
    
            // $.noConflict();
            fetch_data();
    
            setTimeout(function() {
                    $('#flash-message').fadeOut('fast');
                }, 2000); // 2 seconds
            var datatable;
            var library_id = localStorage.getItem('library_id');
    
            $('#library_id').val(library_id);
            // ajax function to get data from api to display at datatable
            function fetch_data() {
                var library_id = localStorage.getItem('library_id');

                datatable = $('#datatablesSimple').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('book.getBookDatatable') }}",
                        data: {
                            library_id: library_id,
                        },
                        type: 'GET',
                    },
                   
                    'columnDefs': [{
                        "targets": [0], // your case first column
                        "className": "text-center",
                        "width": "2%"
                    }, {
                        "targets": [1, 2, 3,4,5,6], // your case first column
                        "className": "text-center",
                    }, ],
                    order: [
                        [1, 'asc']
                    ],
    
                    columns: [{
                        "data": null,
                        searchable: false,
                        "sortable": false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, {
                        data: "name",
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },{
                        data: "genre",
                        name: 'genre',
                        orderable: false,
                        searchable: false
                    },{
                        data: "price",
                        name: 'price',
                        orderable: false,
                        searchable: false
                    },{
                        data: "availability",
                        name: 'availability',
                        orderable: false,
                        searchable: false
                    },{
                        data: "status",
                        name: 'status',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }, ]
                });
            }
            // toggle data status
            $(document).on('change', '.data-status', function() {
            var dataId = $(this).attr('data-id');
            var status = $(this).is(':checked') ? 1 : 0;
    
                $.ajax({
                    url: '/staff/book/' + dataId,
                    type: 'PATCH',
                    data: { 
                        type: "status",
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(result) {
                        // Handle the result of the API call
                        $('#datatablesSimple').DataTable().destroy();
                        fetch_data();
                    }
                });
            });
        });
    </script>
</html>
