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
            <h3>Welcome to UIDAI</h3>
            <p>Enter OTP. To see it in action.</p>
             @include('toast::messages-jquery')
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
            <form class="m-t" role="form" method="POST" action="{{ url('verify-otp') }}">
                {{ csrf_field() }}
                <input type="hidden" name="session_id" value="{{Session::get('session_id')}}">
                 <input type="hidden" name="user_id" value="{{Session::get('user_id')}}">
                 <input type="hidden" name="email" value="{{Session::get('email')}}">
                <div class="form-group{{ $errors->has('otp') ? ' has-error' : '' }}">
                    <input type="text" class="form-control" placeholder="Enter OTP" name="otp" value="{{ old('otp') }}" required autofocus autocomplete="off" maxlength="6" minlength="6">
                    @if ($errors->has('otp'))
                    <span class="help-block">
                        <strong>{{ $errors->first('otp') }}</strong>
                    </span>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-primary block full-width m-b">Submit</button>
                
                <a href="{{ url('/login') }}"><small>Login</small></a>
                
            </p>
        </form>
        <!--  <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p> -->
    </div>
</div>
<!-- Mainly scripts -->
<script src="{!! asset('js/jquery-3.1.1.min.js') !!}"></script>
<script src="{!! asset('js/bootstrap.min.js') !!}"></script>
</body>
</html>
