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
            <p>Login in. To see it in action.</p>
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
            @if (isset($response) && $response['error'] == 1)
            <div class="alert alert-danger">
                {{ $response['message'] }}
            </div>
            @endif
            <form class="m-t" role="form" method="POST" action="{{ route('login') }}" autocomplete="off">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="form-control" placeholder="Username" name="email" value="{{ old('email') }}" required autofocus autocomplete="off">
                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" required autocomplete="off">

                    @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
                
                <button type="submit" onClick="encrypt()" class="btn btn-primary block full-width m-b">Login</button>
                
                <a href="{{ route('password.request') }}"><small>Forgot password?</small></a>
                
                <p class="text-muted text-center">
                    <div class="checkbox">
                       <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>
            </p>
        </form>
        <!--  <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p> -->
    </div>
</div>
<!-- Mainly scripts -->
<script src="{!! asset('js/jquery-3.1.1.min.js') !!}"></script>
<script src="{!! asset('js/bootstrap.min.js') !!}"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/crypto-js.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/enc-base64.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/sha1.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/aes.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function () {
    //Disable cut copy paste
    $('body').bind('cut copy paste', function (e) {
        e.preventDefault();
    });
    
    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});
 function encrypt(){

     const passcode = document.getElementById('password').value; 
     if (passcode !="") {
              
        var sha1Hash = CryptoJS.SHA1(passcode);
        var sha1HashToBase64 = sha1Hash.toString(CryptoJS.enc.Base64);
        document.getElementById('password').value = sha1HashToBase64;
         }
      }
    
   /* $("form").submit(function(){
       $("#password").attr("type", "password");
       var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

       var pass = Base64.encode($("#password").val());
       
       $("#password").val(pass);
       
   });*/


</script>
</body>
</html>
