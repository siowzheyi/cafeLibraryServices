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
        <!-- <link href="./css/styles.css?v=1.0" rel="stylesheet" /> -->
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

        
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark bgtopbar">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">CAFÉ LIBRARY</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Admin</a></li>
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
                                    <a class="nav-link " href="{{ route('book.index') }}">Book</a>
                                    <a class="nav-link " href="{{ route('room.index') }}">Room</a>
                                    <a class="nav-link" href="{{ route('equipment.index') }}">Equipment</a>
                                    <a class="nav-link active" href="{{ route('announcement.index') }}">Announcement</a>

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
                        <h1 class="mt-4">Announcement</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="{{ route('library.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Announcement</li>
                        </ol>
                        @if (Session::has('success'))
                            <div class="alert alert-success" id="flash-message">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" id="flash-message">{{ $error }}</div>
                        @endforeach
                        <div class="flash-message"></div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <a href="{{ route('announcement.create') }}" class="btn btn-success float-end"><i class="fa fa-plus"></i> Create New Announcement</a>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                List of Announcement
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            {{-- <th>Picture</th> --}}
                                            <th>Title</th>
                                            <th>Content</th>
                                            <th>Expired At </th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>

                 {{-- confirmation delete modal --}}
                <div id="deleteConfirmationModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Delete Announcement</h4>
                            </div>
                            <div class="modal-body">
                                Are you sure to delete this announcement?
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal" class="btn btn-primary" id="delete" name="delete">Delete</button>
                                <button type="button" data-bs-dismiss="modal" class="btn">Cancel</button>
                            </div>
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
            
            var library_id = localStorage.getItem('library_id');
            $('#library_id').val(library_id);
            // ajax function to get data from api to display at datatable
            function fetch_data() {
                var library_id = localStorage.getItem('library_id');

                datatable = $('#datatablesSimple').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('announcement.getAnnouncementDatatable') }}",
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
                        "targets": [1, 2, 3,4,5], // your case first column
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
                        data: "title",
                        name: 'title',
                        orderable: true,
                        searchable: true
                    },{
                        data: "content",
                        name: 'content',
                        orderable: false,
                        searchable: false
                    },{
                        data: "expired_at",
                        name: 'expired_at',
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
            $(document).on('click', '.deleteData', function() {
                data_id = $(this).attr('id');
                $('#deleteConfirmationModal').modal('show');
            });

            $('#delete').click(function() {
            $.ajax({
                type: 'DELETE',
                dataType: 'html',
                data: {
                    "_token": "{{ csrf_token() }}",
                    _method: 'DELETE'
                },
                url: "/staff/announcement/" + data_id,
                success: function(data) {
                    setTimeout(function() {
                        $('#confirmModal').modal('hide');
                    }, 2000);

                    $('div.flash-message').html(data);

                    datatable.ajax.reload();
                },
                error: function(data) {
                    $('div.flash-message').html(data);
                }
            })
        });
        });
    </script>
</html>
