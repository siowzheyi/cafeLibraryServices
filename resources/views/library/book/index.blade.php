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
        <link href="css/styles.css?v=1.0" rel="stylesheet" />
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
                            <a class="nav-link" href="staff-library-page.html">
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
                                    <a class="nav-link" href="{{ route('User.index') }}">Staff Library</a>
                                     <a class="nav-link" href="{{ route('Table.index') }}">Table</a>
                                     <a class="nav-link" href="{{ route('Book.index') }}">Book</a>
                                     <a class="nav-link active" href="{{ route('Room.index') }}">Room</a>
                                     <a class="nav-link" href="{{ route('Equipment.index') }}">Equipment</a>
                                    
                                </nav>
                            </div> 
                            
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Library Book</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="staff-library-page.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Library Book</li>
                        </ol>
                       
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                List of Library Book
                            </div>

                            <form id="fileUploadForm" enctype="multipart/form-data" href="{{ route('Book.index') }}">
                                <input type="file" id="fileInput" accept=".xls, .xlsx, .csv, .txt" />
                                <button type="button" onclick="handleFile()">Upload and View</button>
                            </form>
                        
                            <div id="fileDataContainer">
                                <!-- File data will be displayed here -->
                                <table id="dataTable"></table>
                                <div class="pagination" id="pagination"></div>
                            </div>
                        
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
                            </script>

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
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>

    </body>
</html>
