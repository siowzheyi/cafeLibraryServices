<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>CAFÉ LIBRARY SERVICES</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark bgtopbar">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="#">CAFÉ LIBRARY</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            {{-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form> --}}
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Admin</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="login.html">Logout</a></li>
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
                            <a class="nav-link active" href="{{ route('dashboard') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">PAGES</div>
                            <a class="nav-link active" href="{{ route('user.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Staff
                            </a>
                            
                            <a class="nav-link active" href="{{ route('library.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Library
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Cafe</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Cafe</li>
                        </ol>
                        <div class="row mb-2">
                            <div class="col-12">
                                <a href="cafe-create.html" class="btn btn-success float-end"><i class="fa fa-plus"></i> Create New Cafe</a>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                List of Cafe
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Library Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>He and She coffee</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2">
                                                    <label for="exampleFormControlInput1">Created at</label>
                                                    <input type="datetime-local" class="form-control" id="exampleFormControlInput1" value="1st Jan 2024">
                                                </div>
                                            </td>
                                            <td>
                                                <a href="cafe-view.html" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> View</a>
                                                <a href="cafe-edit.html" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                <a href="#" class="btn btn-sm btn-danger" onclick="alert('Successfully deleted.')"><i class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                            
                                        </tr>
                                    </tbody> --}}
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
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
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

            // ajax function to get data from api to display at datatable
            function fetch_data() {
                datatable = $('#datatablesSimple').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('cafe.getCafeDatatable') }}",
                        data: {
                            // library_id: library_id,
                        },
                        type: 'GET',
                    },
                    'columnDefs': [{
                        "targets": [0], // your case first column
                        "className": "text-center",
                        "width": "2%"
                    }, {
                        "targets": [1, 2, 3,4], // your case first column
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
                        render: function(data, type, row, meta) {
                          
                            return '<a href = "/cafe/dashboard/' + row.id + '" id="link' + row.id + '">' + data + '</a>';

                        }
                    },{
                        data: "library_name",
                        name: 'library_name',
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

            $(document).on('click', 'a[id^="link"]', function(e) {
                var id = this.id.replace('link', '');
                localStorage.setItem('cafe_id', id);
            });
            // toggle data status
            $(document).on('change', '.data-status', function() {
            var dataId = $(this).attr('data-id');
            var status = $(this).is(':checked') ? 1 : 0;
    
                $.ajax({
                    url: '/staff/cafe/' + dataId,
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
