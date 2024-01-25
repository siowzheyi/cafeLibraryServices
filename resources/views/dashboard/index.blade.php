

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
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <p>Welcome to admin dashboard.</p>
                        </div>
                    </div>

                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Cafe Report </h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="{{ route('cafe.index') }}">Dashboard Cafe</a></li>
                            <li class="breadcrumb-item active">Cafe Report</li>
                        </ol>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Daily Sales Report
                                    </div>
                                    <div class="card-body">
                                        <canvas id="myAreaChart" width="100%" height="40"></canvas>
                                    </div>
                                    <div class="card-footer small text-muted">Updated at @php  echo date('F j, Y', time() ) @endphp</div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Monthly Sales Report
                                    </div>
                                    <div class="card-body">
                                        <canvas id="myBarChart" width="100%" height="40"></canvas>
                                    </div>
                                    <div class="card-footer small text-muted">Updated at @php  echo date('F j, Y', time() ) @endphp</div>
                                </div>
                            </div>
                        </div>
    
                        </div>
                    </div>
                    
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Library Report </h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="{{ route('library.index') }}">Dashboard Library</a></li>
                            <li class="breadcrumb-item active">Library Report</li>
                        </ol>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie me-1"></i>
                                            Total book by genre
                                    </div>
                                    <div class="card-body">
                                        <canvas id="PieChart" width="80%" height="200"></canvas>
                                    </div>
                                    <div class="card-footer small text-muted">Updated at @php  echo date('F j, Y', time() ) @endphp</div>
                                </div>
                            </div>
                        </div>

                        </div>
                    </div>
                </main>

        

                </div>        

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
 <script src="{{url('public/vendor/create-charts.js')}}"></script>
 <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <canvas id="myChart"></canvas>
    <script>
    
        // // Call the updateCharts function when the page loads
        $(document).ready(function () {
                // Make AJAX requests to get data and update charts
            function updateCharts() {
                // Daily Sales Chart
                $.ajax({
                    url: "{{ route('order.getDailySales') }}",
                    data: {
                            // cafe_id: cafe_id,
                        },
                    type: 'GET',
                    success: function (data) {
                        updateAreaChart('myAreaChart', data);
                    },
                    error: function (error) {
                        console.error('Error fetching daily sales data:', error);
                    }
                });

                // Monthly Sales Chart
                $.ajax({
                    url: "{{ route('order.getMonthlySales') }}",
                    data: {
                            // cafe_id: cafe_id,
                        },
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        updateBarChart('myBarChart', data);
                    },
                    error: function (error) {
                        console.error('Error fetching monthly sales data:', error);
                    }
                });

                // Book Chart
                $.ajax({
                    url: "{{ route('book.getGenreBook') }}",
                    data: {
                            // library_id: library_id,
                        },
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        updateBookPieChart('PieChart', data);
                    },
                    error: function (error) {
                        console.error('Error fetching genre book data:', error);
                    }
                });

               
            }

            // Function to update the area chart
            function updateAreaChart(chartId, data) {
                var ctx = document.getElementById(chartId).getContext('2d');
                // Extracting date and total_price from JSON data
                var labels = data.map(item => item.date);
                var totalPriceData = data.map(item => item.total_price);
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Sales',
                            data: totalPriceData,
                            fill: true, // Set to true to fill the area under the line
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                type: 'time', // Assuming 'date' represents a time value
                                time: {
                                    unit: 'day',
                                    tooltipFormat: 'yyyy-MM-dd', // Adjust tooltip format as needed
                                    displayFormats: {
                                        day: 'yyyy-MM-dd'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

    
            // Helper function to update bar chart
            function updateBarChart(chartId, data) {
                var ctx = document.getElementById(chartId).getContext('2d');

                // Extract 'month' and 'total_sales' properties from the array of objects
                var labels = data.map(item => item.month);
                var salesData = data.map(item => item.total_sales);
                console.log("inside update bar chart, get total price data:" + salesData);
                console.log("inside update bar chart, get tdate data:" + labels);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Sales',
                            data: salesData,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                type: 'category'
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            function updateBookPieChart(chartId, data) {
                var ctx = document.getElementById(chartId).getContext('2d');

                // Extract 'genre' and 'total_books' properties from the array of objects
                var labels = data.map(item => item.genre);
                var totalBooksData = data.map(item => item.total_books);

                console.log("inside update pie chart, get total books data:" + totalBooksData);
                console.log("inside update pie chart, get genre data:" + labels);

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Books',
                            data: totalBooksData,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'right'
                        }
                    }
                });
            }

            updateCharts();
        });
    </script>

</body>
</html>
