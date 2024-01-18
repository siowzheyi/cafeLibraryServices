<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>CAFÉ LIBRARY SERVICES</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark bgtopbar">
        <a class="navbar-brand ps-3">CAFÉ LIBRARY</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item">Admin</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="{{ route('login') }}">Logout</a></li>
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
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">PAGES</div>
                        <a class="nav-link collapsed active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsLib" aria-expanded="false" aria-controls="collapseLayoutsLib">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Staff
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse bgsubmenu show" id="collapseLayoutsLib" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <button id="staffadmin">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                    Staff Admin
                                </button>
                                <button id="staffcafe">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                    Staff Cafe
                                </button>
                                <button id="stafflibrary">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                    Staff Library
                                </button>
                            </nav>
                        </div> 
                        <a class="nav-link active" href="{{ route('library.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Library
                        </a>
                        <a class="nav-link active" href="{{ route('cafe_daily_sales_report.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Report
                        </a>
                        <div class="collapse show" id="collapseLayoutsCafe" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                {{-- <a class="nav-link" href="{{ route('User.index') }}">Staff Cafe</a> --}}
                                {{-- <a class="nav-link active " href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsCafe" aria-expanded="false" aria-controls="collapseLayoutsCafe">
                                    <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                                    Report
                                    <div class="sb-sidenav-collapse-arrow"><i class=""></i></div>
                                </a> --}}
                                <div class="collapse show" id="collapseLayoutsCafe" aria-labelledby="card-header" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link menu" href="{{ route('cafe_daily_sales_report.index') }}" >Daily Sales Cafe</a>
                                        <a class="nav-link menu" href="{{ route('cafe_detail_sales_report.index') }}">Detail Sales Cafe</a>
                                        <a class="nav-link menu" href="{{ route('library_penalty_report.index') }}">Penalty Report Library</a>

                                    </nav>
                                </div> 
                            </nav>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Staff</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Staff</li>
                    </ol>
                    @if (Session::has('message-success'))
                        <div class="alert alert-success" id="flash-message">
                            {{ Session::get('message-success') }}
                        </div>
                    @endif
                    <div class="row mb-2">
                        <div class="col-12">
                            <a href="{{ route('user.create') }}" class="btn btn-success float-end"><i class="fa fa-plus"></i> Create New Staff</a>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            List of staff
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone No</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <!-- Update the table body to loop through users -->
                                
                            </table>
                        </div>
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
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('demo/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
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
        var type;

        // destroy datatable and reload again
        $('#staffadmin').click(function() {
            type = null;
            $('#datatablesSimple').DataTable().destroy();
            fetch_data();
        });
        $('#staffcafe').click(function() {
            type = "cafe";
            $('#datatablesSimple').DataTable().destroy();
            fetch_data();
        });
        $('#stafflibrary').click(function() {
            type = "library";
            $('#datatablesSimple').DataTable().destroy();
            fetch_data();
        });

        // ajax function to get data from api to display at datatable
        function fetch_data() {
            datatable = $('#datatablesSimple').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.getUserDatatable') }}",
                    data: {
                        type: type,
                    },
                    type: 'GET',

                },
               
                'columnDefs': [{
                    "targets": [0], // your case first column
                    "className": "text-center",
                    "width": "2%"
                }, {
                    "targets": [1, 2, 3, 4], // your case first column
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
                }, {
                    data: "phone_no",
                    name: 'phone_no',
                    orderable: false,
                    searchable: true
                }, {
                    data: "email",
                    name: 'email',
                    orderable: false,
                    searchable: true
                }, {
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

        // toggle user status
        $(document).on('change', '.user-status', function() {
        var userId = $(this).attr('data-user-id');
        var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '/user/' + userId,
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
