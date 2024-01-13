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
                    <li><a class="dropdown-item">Cafe Staff</a></li>
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
                        <a class="nav-link" href="{{ route('cafestaff.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">PAGES</div>
                        
                        <a class="nav-link collapsed active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsCafe" aria-expanded="false" aria-controls="collapseLayoutsCafe">
                            <div class="sb-nav-link-icon"><i class="fas fa-cutlery"></i></div>
                            Cafe
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapseLayoutsCafe" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('cafestaffindex') }}">Staff Cafe</a>
                                <a class="nav-link collapsed active" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsCafe" aria-expanded="false" aria-controls="collapseLayoutsCafe">
                                    <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                                    Beverage
                                    <div class="sb-sidenav-collapse-arrow"><i class=""></i></div>
                                </a>
                                <div class="collapse show" id="collapseLayoutsCafe" aria-labelledby="card-header" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ route('hotcoffeeindex') }}">Hot Coffee</a>
                                    <a class="nav-link" href="{{ route('icecoffeeindex') }}">Ice Coffee</a>
                                    <a class="nav-link" href="{{ route('blendedindex') }}">Blended Coffee</a>
                                    <a class="nav-link" href="{{ route('smoothieindex') }}">Smoothies</a>
                                    <a class="nav-link" href="{{ route('cakeindex') }}">Cake</a>
                                    <a class="nav-link" href="{{ route('breadindex') }}">Bread</a>
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
                    <h1 class="mt-4">View Cafe Staff</h1>
                    <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="{{ route('cafestaff.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Cafe Staff</li>
                        <li class="breadcrumb-item"><a href="{{ route('cafestaffindex') }}">Staff Cafe</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4>Staff Detail</h4>
                            <table width="100%">
                                <tr>
                                    <td width="15%">Name</td>
                                    <td width="5%">:</td>
                                    <td width="80%">{{ $staff->name }}</td>
                                </tr>
                                <tr>
                                    <td>Phone Number</td>
                                    <td>:</td>
                                    <td>{{ $staff->phone_number }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ $staff->email }}</td>
                                </tr>
                                <tr>
                                    <td>Cafe ID</td>
                                    <td>:</td>
                                    <td>{{ $staff->cafe_id }}</td>
                                </tr>
                                {{-- <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td><span class="badge {{ $staff->status == 'Active' ? 'bg-success' : 'bg-danger' }}">{{ $staff->status }}</span></td>
                                </tr> --}}
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
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
</body>
</html>
