<!DOCTYPE html>

@php
    $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI1IiwianRpIjoiOTIzNWM2NzBiNzY4ZmE0NWRlNzAzYzQ0N2I3ZjQ0NTA4MjkzOWNjYmVkMmVmZGM4NDQ5ZmI4OWZlMzE3ZDQ2ZWZhNTRhOGJkNzVhM2ZhOTUiLCJpYXQiOjE3MDQ5NTc0ODkuODAwNjEyLCJuYmYiOjE3MDQ5NTc0ODkuODAwNjE1LCJleHAiOjE3MDc2MzU4ODkuNzY5OTM2LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.OFfKVUwVxBzOIgyJfEcgReO1E8_smqDEM5-qo6hR0qVAQTNJeC24oyXyIyDVaKzWjZgbuLo8QALMYG_614bSktyG3tnToYC5y2uTpR45ezZEmHVxycu816YAp7x7nOMZepp1WACU-MFLCqhHPk3MwIfUzHTKYehwm8Ug4tF9MlND7lGIERbXGt70RNjJUno573dbkFaM1UULmV_M2RgdYiKS7wE6GeVKaOPqDOn-yJsjKlaxo7xWvtGRrauhu3zOhqZNVj7_ugyj7rkdWaHwueC59yG2ZblA_Mj5S-1_3fH5XG9cYbpQB-W9Ks8-4sGZW5DdBdMrgzHvV1Be-AiRjpYAaxp2AxKEXfBP0Wab4XC-ayIbLKvCcBpej-X1UR7HgBNw5KLoZrSUzY_LV7Kt3RWMxqgkyMjOfh416CUscG_TbeQDfm-U1Aw2vYxSb8QYmoGplGg80N6Ve29ITpwaz8RHDGUIG86Zq4v4Hu9ZagH8pS-k01Q1y1owrQEBHJBrEdnuOvq8Ddug1WVK5ctYwNg5d4eFrYxZIXdwHObF3te7jI2XkvmKp4FnsJ9i-0a4FdmGcWRLCktJXvxWd7YhcOi0ftbF8dHgMl7gpM2vz07YJjggwHkFCphkYZ9VmAt3gyaqUTxefCx6CdWiun0HFAq_5ff-C8aS9pOZQEwMGmg';
    $data = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->get('/api/announcement')->json();
@endphp
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
    {{-- {{ $announcements['iTotalRecords'] }} --}}
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark bgtopbar">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3">CAFÉ LIBRARY</a>
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
                        <a class="nav-link" href="{{ route('admin.dashboard') }}>
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">PAGES</div>
                        {{-- <a class="nav-link" href="{{ route('staffindex') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Staff Admin
                        </a>
                        <a class="nav-link" href="{{ route('cafestaffindex') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Staff Cafe
                        </a>
                        <a class="nav-link" href="{{ route('libstaffindex') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Staff Library
                        </a>
                        <a class="nav-link active" href="{{ route('annoucementindex') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-podcast"></i></div>
                            Announcement
                        </a> --}}
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Announcement</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Announcement</li>
                    </ol>
                    <div class="row mb-2">
                        <div class="col-12">
                            <a href="{{ route('announcementstore') }}" class="btn btn-success float-end"><i class="fa fa-plus"></i> Create New Announcement</a>
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
                                        <th>Picture</th>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Expired </th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach($announcements['aaData'] as $announcement)
                                        <tr>
                                            <td>{{ $announcement->id }}</td>
                                            <td><img src="{{ asset('images/announcement.jpeg') }}" width="100"></td>
                                            <td>{{ $announcement->title }}</td>
                                            <td>{{ $announcement->content }}</td>
                                            <td>{{ $announcement->expired_at }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" {{ $announcement->status ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">{{ $announcement->status ? 'Active' : 'Inactive' }}</label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('announcementshow', $announcement->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> View</a>
                                                <a href="{{ route('announcementupdate', $announcement->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                <a href="#" class="btn btn-sm btn-danger" onclick="alert('Successfully deleted.')"><i class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
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
