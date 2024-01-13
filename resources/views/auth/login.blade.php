<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- <meta name="csrf-token" content="{{ csrf_token }}"> --}}
    <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>CAFÉ LIBRARY SERVICES</title>
        {{-- <link href="css/styles.css" rel="stylesheet" /> --}}
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <style type="text/css">
        body {
        	background-image: url("{{ asset('img/bg-login.jpg') }}");
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
                            <div class=""><h2 class="text-center font-weight-light my-4">CAFÉ LIBRARY SERVICES</h2></div>
                            <div class="card-header main-bgcolor"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                            <div class="card-body">
                                @if(session('status'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                               
                                {{-- Hello, i'm here: data: {{ $data }}; --}}

                                <form id="loginForm" method="post" action="{{ route('login.post') }}">
                                    @csrf
                                    
                                    {{-- <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" required> --}}

                                    <div class="input-group mb-3">
                                        <input id="email" type="email" placeholder="E-mail" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                            </div>
                                        </div>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="input-group mb-3">
                                        <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="row" style="margin-left: 10px;">
                                        <div class="col-8">
                                            <div class="icheck-primary">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label for="remember">
                                                    Remember Me
                                                </label>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                                        </div>
                                        <!-- /.col -->
                                    </div>

                                </form>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('js/scripts.js') }}"></script>

</body>
</html>
