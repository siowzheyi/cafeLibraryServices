<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>CAFÉ LIBRARY SERVICES</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> --}}

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
                        
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsCafe" aria-expanded="false" aria-controls="collapseLayoutsCafe">
                            <div class="sb-nav-link-icon"><i class="fas fa-cutlery"></i></div>
                            Cafe
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapseLayoutsCafe" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                {{-- <a class="nav-link" href="{{ route('User.index') }}">Staff Cafe</a> --}}
                                <a class="nav-link collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsCafe" aria-expanded="false" aria-controls="collapseLayoutsCafe">
                                    <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                                    Menu
                                    <div class="sb-sidenav-collapse-arrow"><i class=""></i></div>
                                </a>
                                <div class="collapse show" id="collapseLayoutsCafe" aria-labelledby="card-header" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link menu" href="{{ route('beverage.index') }}" >Hot Coffee</a>
                                        <a class="nav-link menu" href="{{ route('beverage.index') }}">Ice Coffee</a>
                                        <a class="nav-link menu" href="{{ route('beverage.index') }}">Blended Coffee</a>
                                        <a class="nav-link menu" href="{{ route('beverage.index') }}">Smoothies</a>
                                        <a class="nav-link menu" href="{{ route('beverage.index') }}">Cake</a>
                                        <a class="nav-link menu" href="{{ route('beverage.index') }}">Bread</a>
                                    </nav>
                                </div> 
                              
                            </nav>
                        </div> 
                        <a class="nav-link" href="{{ route('order.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Order
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
                                        <a class="nav-link menu" href="{{ route('daily_sales_report.index') }}" >Daily Sales</a>
                                        <a class="nav-link menu" href="{{ route('detail_sales_report.index') }}">Detail Sales</a>
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
                    <h1 class="mt-4">Cafe Report</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('cafe.dashboard') }}">Dashboard</a></li>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
 <script src="{{url('public/vendor/create-charts.js')}}"></script>
 <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        

        updateCharts();
    });
</script>
</body>
</html>
