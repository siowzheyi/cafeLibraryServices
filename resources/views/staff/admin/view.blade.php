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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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
                        <a class="nav-link" href="{{ route('user.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Staff Admin
                        </a>
                        <a class="nav-link" href="{{ route('user.index',['type'=>'cafe']) }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Staff Cafe
                        </a>
                        <a class="nav-link" href="{{ route('user.index',['type'=>'library']) }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Staff Library
                        </a>
                        
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4"> Staff Details</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Staff</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>

                    @if (Session::has('message-error'))
                        <div class="alert alert-danger" id="flash-message">
                            {{ Session::get('message-error') }}
                        </div>
                    @endif
                    @if (Session::has('message-success'))
                        <div class="alert alert-success" id="flash-message">
                            {{ Session::get('message-success') }}
                        </div>
                    @endif
                   
                    <div class="row mb-4">
                        <div class="col-12">
                            <form action="{{ route('user.update',['user' => $data['id']]) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="form-group mb-2">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $data['name'] }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="phone_number">Phone number</label>
                                    <input type="phone_number" class="form-control" id="phone_number" name="phone_no" value="{{ $data['phone_no'] != "null" ? $data['phone_no'] : '' }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" value="{{ $data['email'] }}">
                                </div>
                                @if ( $data['cafe_id']  != null)
                                <div class="form-group mb-2">
                                    <label for="building_name">Cafe name</label>
                                    <input type="text" class="form-control" id="building_name" name="building_name" value="{{ $data['building_name'] }}" readonly>
                                </div>
                                @elseif ( $data['library_id']  != null)
                                <div class="form-group mb-2">
                                    <label for="building_name">Library name</label>
                                    <input type="text" class="form-control" id="building_name" name="building_name" value="{{ $data['building_name'] }}" readonly>
                                </div>
                                @endif
                                <br>
                                <button type="submit" class="btn btn-success mb-2" >Update</button>
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
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('demo/chart-bar-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#flash-message').fadeOut('fast');
            }, 2000); // 2 seconds
        });

    </script>
    
</body>
</html>
