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
                    <h1 class="mt-4">Cafe Staff</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('cafestaff.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Cafe Staff</li>
                    </ol>
                    {{-- <div class="row mb-2">
                        <div class="col-12">
                            <a href="{{ route('cafestaffstore') }}" class="btn btn-success float-end"><i class="fa fa-plus"></i> Create New cafe Staff</a>
                        </div>
                    </div> --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            List of staff cafe
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
<tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->phone_no }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" {{ $user->isActive ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexSwitchCheckChecked">{{ $user->isActive ? 'Active' : 'Inactive' }}</label>
                </div>
            </td>
            <td>
                <a href="{{ route('staffshow', $user->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> View</a>
                <a href="{{ route('staffupdate', $user->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                <a href="#" class="btn btn-sm btn-danger" onclick="alert('Successfully deleted.')"><i class="fa fa-trash"></i> Delete</a>
            </td>
        </tr>
    @endforeach

    <!-- Update the table body to loop through API data if available -->
    @isset($apiData)
        @foreach($apiData as $item)
            <tr>
                <td>{{ $item['id'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['phone_no'] }}</td>
                <td>{{ $item['email'] }}</td>
                <!-- Add other fields as needed -->
            </tr>
        @endforeach
    @endisset
</tbody>
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
