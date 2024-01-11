<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>CAFÉ LIBRARY SERVICES</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <style type="text/css">
        body {
        	background-image: url("assets/img/bg-login.jpg");
        }
        .main-bgcolor {
        	background-color: #47d147;
        	color: #ffffff;
        }
        </style>

    </head>
    <body>


    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header main-bgcolor"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                <div class="card-body">
                                    Hello, i'm here: data: {{ $data }};


    <form id="loginForm" method="post" action="{{ route('login') }}">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <br>
        <p></p>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <p></p>
        <br>
        
        <div class="form-check mb-3">
            <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
            <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
            <a class="small" href="password.html">Forgot Password?</a>
            <a class="btn btn-primary" onclick="authenticate()">Login</a>
        </div>

        
    </form>

    <script>
        function authenticate() {
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        // Example roles and credentials (replace with your actual backend logic)
        var roles = {
            "admin": { username: "admin", password: "admin123" },
            "staffcafe": { username: "cafestaff", password: "cafe123" },
            "stafflibrary": { username: "librarystaff", password: "library123" }
        };

        // Check if the provided credentials match any role
        if (roles.admin.username === username && roles.admin.password === password) {
            alert("Login successful for Admin");
        //    window.location.href = "dashboard.html"; // Redirect to Admin page

        //    window.location.href = "{{ route('login') }}";
        //    console.log("123");
        } else if (roles.staffcafe.username === username && roles.staffcafe.password === password) {
            alert("Login successful for Staff Cafe");
            window.location.href = "staff-cafe-page.html"; // Redirect to User page
        } else if (roles.stafflibrary.username === username && roles.stafflibrary.password === password) {
            alert("Login successful for Staff Library");
            window.location.href = "staff-library-page.html"; // Redirect to Guest page
        } else {
            alert("Invalid credentials");
        }
        }
    </script>
    </div>
    <div class="card-footer text-center py-3">
        <div class="small">Admin Dashboard</div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </main>
        </div>
        </div>
        <div id="layoutAuthentication_footer">
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

    </body>
</html>
