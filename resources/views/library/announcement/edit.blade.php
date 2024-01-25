<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <!--title-->
        <title>CAFÉ LIBRARY SERVICES</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark bgtopbar">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">CAFÉ LIBRARY</a>
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
                            <a class="nav-link" href="{{route('penalty_report.index')}}">
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
                        <h1 class="mt-4">Edit Announcement</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="{{ route('library.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('announcement.index') }}">Announcement</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" id="flash-message">{{ $error }}</div>
                        @endforeach
                        <div class="row mb-4">
                            <div class="col-12">
                                <form action="{{ route('announcement.update',['announcement'=>$data['id']]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group mb-2">
                                        <label for="exampleFormControlInput1">Title</label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1" name="title" value="{{ $data['title'] }}">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="exampleFormControlInput1">Content</label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1" name="content" value="{{ $data['content'] }}">
                                            
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="exampleFormControlInput1">Expired</label>
                                        <input type="datetime-local" class="form-control" id="exampleFormControlInput1" name="expired_at" value="{{ $data['expired_at'] }}">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="exampleFormControlInput1">Picture</label>
                                        <br><img src="{{ $data['picture'] }}" width="200">
                                        <input type="file" class="form-control mt-2" id="exampleFormControlInput1" name="picture">
                                    </div>
                                    <input type="submit" class="btn btn-success mb-2" value="Submit">
                                </form>
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
        <script src="js/datatables-simple-demo.js"></script>

    </body>
</html>
