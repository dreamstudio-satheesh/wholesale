<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Login page">

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <title> {{ config('app.name') }} - Login in</title>

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500"
        rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />

    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href="{{ asset('themes/sleek/assets') }}/css/sleek.css" />

    <!-- FAVICON -->
    <link href="{{ asset('themes/sleek/assets') }}/img/favicon.png" rel="shortcut icon" />

    <!--
      HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
    -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{{ asset('themes/sleek/assets') }}/plugins/nprogress/nprogress.js"></script>
</head>

<body class="" id="body" style="background-image:url('{{ url('image/nature.webp') }}')">

    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-10">
                <div class="card" style="min-width: 400px">
                    <div class="card-header bg-primary">
                        <div class="app-brand">
                            <a href="{{ route('home') }}">
                                <img src="{{ url('image/logo.png')}}" alt="Elite POS Logo" style="height: 50px;">

                                <span class="brand-name">{{ config('app.name') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-5">
                        <h4 class="text-dark mb-5">Sign In</h4>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-12 mb-4">
                                    <input type="text" class="form-control input-lg" name="username"
                                        placeholder="Username" value="admin" autofocus>

                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12 ">
                                    <input type="password" class="form-control input-lg" name="password"
                                        placeholder="Password" value="password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="d-flex my-2 justify-content-between">
                                        <div class="d-inline-block mr-3">
                                            <label class="control control-checkbox">Remember me
                                                <input type="checkbox" name="remember" id="remember"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <div class="control-indicator"></div>
                                            </label>
                                        </div>

                                        
                                    </div>
                                    <button type="submit" class="btn btn-lg btn-primary btn-block mb-4">
                                        {{ __('Sign In') }}
                                    </button>
                                   
                                   
                                </div>
                            </div>
                        </form>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- <script type="module">
        import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';

        const el = document.createElement('pwa-update');
        document.body.appendChild(el);
    </script> -->

    <!-- Javascript -->
    <script src="{{ asset('themes/sleek/assets') }}/plugins/jquery/jquery.min.js"></script>
    <script src="{{ asset('themes/sleek/assets') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('themes/sleek/assets') }}/js/sleek.js"></script>
</body>

</html>
