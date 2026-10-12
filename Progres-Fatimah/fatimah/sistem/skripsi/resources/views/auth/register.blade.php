<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sufee Admin - HTML5 Admin Template</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{ asset('style/assets/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('style/assets/scss/style.css') }}">
    
    <!-- Import font Inknut Antiqua dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: url('{{ asset('images/bg_puskesmas.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-content {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .login-logo img {
            padding-top:50px;
            width: 150px;
            margin-bottom: 10px; /* Kurangi jarak bawah */
        }
        /* Teks Pharmaclassify */
        .login-title {
            text-align: center;
            color: white;
            font-family: 'Inknut Antiqua', serif;
            font-size: 36px;
            font-weight: 700;
            text-shadow: 2px 2px 0px black;
            margin-bottom: 10px; /* Kurangi jarak antara teks dan logo */
        }
        .login-form h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>


<body>


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                

                
               
           
               
                    <!-- Teks Pharmaclassify -->
                    <div class="login-title">PharmaPredict</div>
                

                <div class="login-form">
                    <h2 class="text-center">REGISTER</h2>
                    <!-- Form untuk register -->
                    <form method="POST" action="{{ route('register') }}">
                    @csrf
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Full Name" :value="old('name')" required autofocus autocomplete="name">
                        </div>
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" :value="old('email')" required autocomplete="username">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" required autocomplete="new-password">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Register</button>
                    </form>

                    <div class="register-link m-t-15 text-center">
                        <p>Already have an account? <a href="{{route('login')}}">Sign in Here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('style/assets/js/vendor/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('style/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('style/assets/js/main.js') }}"></script>

</body>
