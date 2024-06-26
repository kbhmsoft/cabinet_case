<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">


<meta charset="utf-8"/>
<title>লগইন | {{ config('app.name') }}</title>
    <style>


        .login-page {
            width: 360px;
            padding: 8% 0 0;
            margin: auto;
        }

        .form {
            position: relative;
            margin-top: 118px !important;
            z-index: 1;
            background: #FFFFFF;
            max-width: 360px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }

        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #4CAF50;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
        }

        .form button:hover,
        .form button:active,
        .form button:focus {
            background: #43A047;
        }

        .form .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
        }



        .form .register-form {
            display: none;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 300px;
            margin: 0 auto;
        }

        .container:before,
        .container:after {
            content: "";
            display: block;
            clear: both;
        }

        .container .info {
            margin: 50px auto;
            text-align: center;
        }

        .container .info h1 {
            margin: 0 0 15px;
            padding: 0;
            font-size: 36px;
            font-weight: 300;
            color: #1a1a1a;
        }

        .container .info span {
            color: #4d4d4d;
            font-size: 12px;
        }

        .container .info span a {
            color: #000000;
            text-decoration: none;
        }

        .container .info span .fa {
            color: #EF3B3A;
        }
        .small-text-danger {
            color: red;
            font-size: 12px; /* Adjust the font size as needed */
        }
        /* body {
            background: #76b852;
            /* fallback for old browsers */
            /* background: rgb(141, 194, 111);
            background: linear-gradient(90deg, rgba(141, 194, 111, 1) 0%, rgba(118, 184, 82, 1) 50%);
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale; */
        /* } */
    </style>
    <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}"/>
</head>

<body>

    <div class="form">
        <div class="logo">
            <a href="#">
                <img src="{{ asset('images/logo.png') }}" style="width: 100%;" alt="" />
            </a>
        </div>
        <form method="POST" action="{{ route('doptor.login') }}">
            @csrf
            <div class="form-group otp-hidden">
                <div class="input-icon">
                    <i class="fa fa-user"></i>
                    <input id="login" type="text"
                           class="form-control placeholder-no-fix @error('login') is-invalid @enderror" name="login"
                           placeholder="ইমেইল অথবা ফোন নম্বর" value="{{ old('login') }}" required autofocus />
                    @error('login')
                        <span class="small-text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group otp-hidden">
                <div class="input-icon">
                    <i class="fa fa-lock"></i>
                    <input class="form-control placeholder-no-fix @error('password') is-invalid @enderror" required
                           autocomplete="current-password" type="password" placeholder="পাসওয়ার্ড" id="password"
                           name="password" />
                    @error('password')
                        <span class="small-text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-actions otp-hidden submit_loader">
                <button type="submit" id="submit" class="btn pull-right" onclick="buttonDisable()">
                    <i class="a2i_gn_login2"></i> লগইন করুণ
                </button>
            </div>
        </form>


        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}






    </div>
    </div>
    <script src="{{ asset('/login_assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/login_assets/global/plugins/jquery-migrate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/login_assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/login_assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/login_assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/login_assets/global/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('/login_assets/global/scripts/metronic.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/login_assets/admin/layout/scripts/layout.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/login_assets/admin/layout/scripts/demo.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/login_assets/admin/pages/scripts/login.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/login_assets/admin/pages/scripts/login-soft.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        function buttonDisable() {
            $('.submit_loader').append('<b class="pull-right"><img src="{{ asset('media/loading/loading-load.gif') }}" style="width: 20px;" alt="" /></b>');
            $('.submit_loader').append('<b class="pull-right">অপেক্ষা করুণ...</b>');
            $('#submit').hide();
            return true;
        }
    </script>

</body>

</html>
