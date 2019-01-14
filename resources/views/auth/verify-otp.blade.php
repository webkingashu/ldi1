<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UIDAI</title>
    <link href="{!! asset('css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('font-awesome/css/font-awesome.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/animate.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/style.css') !!}" rel="stylesheet">
</head>
<body class="gray-bg">
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name"></h1>
            </div>
            <h3>Enter OTP</h3>
            <p>OTP has been sent to the registered Mobile Number.</p>

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('danger'))
            <div class="alert alert-danger">
                {{ session('danger') }}
            </div>
            @endif


            <form role="form" method="POST" action="/two-face-authentication">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input id="2fa" type="text" class="form-control" name="2fa" placeholder="Enter the code you received here." required autofocus>
                    @if ($errors->has('2fa'))
                        <span class="help-block">
                            <strong>{{ $errors->first('2fa') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <button class="btn btn-primary block full-width m-b" type="submit">Send</button>
                </div>
            </form>
        </div>
        </div>
    <!-- Mainly scripts -->
    <script src="{!! asset('js/jquery-3.1.1.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap.min.js') !!}"></script>

</body>
</html>
