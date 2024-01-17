<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
        <a class="navbar-brand ps-3">CAFÉ LIBRARY</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
       
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item">Staff Cafe</a></li>
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
                        <div class="collapse show" id="collapseLayoutsCafe" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                {{-- <a class="nav-link" href="{{ route('User.index') }}">Staff Cafe</a> --}}
                                <a class="nav-link collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsCafe" aria-expanded="false" aria-controls="collapseLayoutsCafe">
                                    <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                                    Report
                                    <div class="sb-sidenav-collapse-arrow"><i class=""></i></div>
                                </a>
                                <div class="collapse show" id="collapseLayoutsCafe" aria-labelledby="card-header" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link menu active" href="{{ route('cafe_daily_sales_report.index') }}" >Daily Sales Cafe</a>
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
                    <h1 class="mt-4">Cafe Beverage</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Report</li>
                    </ol>
                    @if (Session::has('success'))
                        <div class="alert alert-success" id="flash-message">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" id="flash-message">{{ $error }}</div>
                    @endforeach
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Daily Sales Report
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Created At</th>
                                        <th>Cafe Name</th>
                                        <th>Total Transaction</th>
                                        <th>Total Subtotal</th>
                                        <th>Grand Total</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Modal -->
            <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <!-- Modal content goes here -->
                  </div>
                </div>
              </div>
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
        var cafe_id = localStorage.getItem('cafe_id');

        // destroy datatable and reload again
        
        $('#cafe_id').val(cafe_id);
        // ajax function to get data from api to display at datatable
        function fetch_data() {
            var cafe_id = localStorage.getItem('cafe_id');

            datatable = $('#datatablesSimple').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('daily_sales_report') }}",
                    data: {
                        // cafe_id: cafe_id,
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
                columns: [ {
                    data: "date",
                    name: 'date',
                    orderable: true,
                    searchable: false
                },{
                    data: "cafe_name",
                    name: 'cafe_name',
                    orderable: true,
                    searchable: true
                }, {
                    data: "total_transaction",
                    name: 'total_transaction',
                    orderable: true,
                    searchable: true
                },{
                    data: "subtotal",
                    name: 'subtotal',
                    orderable: false,
                    searchable: false
                },{
                    data: "total_price",
                    name: 'total_price',
                    orderable: false,
                    searchable: false
                } ]
            });
        }
        // toggle data status
        $(document).on('change', '.data-status', function() {
        var dataId = $(this).attr('data-id');
        var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '/staff/order/' + dataId,
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
