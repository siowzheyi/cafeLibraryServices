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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Fetch data from API
            fetch("your_api_endpoint")
                .then(response => response.json())
                .then(data => {
                    // Process data from the API
                    console.log(data);

                    // Update the table dynamically
                    const tableBody = document.getElementById("datatablesBody");
                    tableBody.innerHTML = ""; // Clear existing rows

                    data.forEach(row => {
                        const newRow = document.createElement("tr");
                        newRow.innerHTML = `
                            <td>${row.id}</td>
                            <td>${row.name}</td>
                            <td>${row.phone_no}</td>
                            <td>${row.email}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheck${row.id}" ${row.status === "Active" ? 'checked' : ''}>
                                    <label class="form-check-label" for="flexSwitchCheck${row.id}">${row.status}</label>
                                </div>
                            </td>
                            <td>
                                <a href="staff-view.html" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> View</a>
                                <a href="staff-edit.html" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                <a href="#" class="btn btn-sm btn-danger" onclick="alert('Successfully deleted.')"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        `;
                        tableBody.appendChild(newRow);
                    });
                })
                .catch(error => {
                    console.error("Error fetching data from API:", error);
                });
        });
    </script>
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
        @if (Session::has('message-success'))
            <div class="alert alert-success" id="flash-message">
                {{ Session::get('message-success') }}
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-12">
                <form action="{{ route('user.store')}}" method="post">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="form-group mb-2">
                        <label for="phone_number">Phone number</label>
                        <input type="phone_number" class="form-control" id="phone_number" name="phone_no" value="">
                    </div>
                    <div class="form-group mb-2">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="">
                    </div>
                    <div class="form-group mb-2">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" id="password" name="password" value="">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success mb-2" >Submit</button>
                </form>
            </div>
        </div>
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
