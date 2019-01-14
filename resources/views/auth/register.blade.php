@extends('layouts.app')
@section('title', 'User Register')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox">

         <div class="ibox-title col-md-12 display-flex">
            <h3 class="col-md-10"><strong> Users</strong></h3>
            <div class="ibox-tools col-md-2">
                <a href="{{ url('/users') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
            </div>
        </div>
        <?php if(isset($users->id) && !empty($users->id)) { 
            $id = $users->id; }
            if(isset($is_update_url) && $is_update_url==1 && isset($id)) {
                $url = '/users/'.$id;
                $action = 'POST';
            } else {
              $url = '/register';
              $action = 'POST';
          }       ?>    
          <div class="ibox-content">
            <form method="<?php echo $action; ?>" class="form-horizontal" @if(isset($is_update_url) && $is_update_url==1)   action="{{url($url)}}" @else  action="{{ route('register') }}"  @endif>
                {{csrf_field()}}
                @if(isset($is_update_url) && $is_update_url==1) {{ method_field('PATCH') }}@endif
                <div class="form-group">
                    <div class="col-md-3">
                        <label class="control-label">Name<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" required class="form-control"  placeholder="Name" <?php if(isset($users->name ) && !empty($users->name)){?> value="{{ $users->name}}"
                            <?php } else { ?> value="{{old('name')}}" <?php } ?> >
                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Email<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="email" id="email" required name="email" class="form-control" placeholder="Email" <?php if(isset($users->email ) && !empty($users->email)){?> value="{{ $users->email}}"
                            <?php } else { ?> value="{{old('email')}}" <?php } ?> >
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                        <div class="col-md-3">
                       <label class="control-label">Role<span class="e-color">*</span></label>
                       <div class="{{ $errors->has('role_id') ? ' has-error' : '' }}">
                        <select class="form-control chosen-select disabled-select" id ="role_id" name="role_id[]" multiple data-placeholder="Select Role" >
                            <?php  if (isset($roles_list) && count($roles_list) > 0) : ?>
                            
                            <?php foreach ($roles_list as  $value) : ?>
                               <option @if(isset($users['role_id']))
                                  @foreach($users['role_id'] as $selected_roles) <?php if(isset($selected_roles['id']) && !empty($selected_roles['id']) && $selected_roles['id'] == $value->id) { echo 'selected';  } ?> @endforeach @endif value="{{$value->id}}" >{{$value->role_name}} ({{$value->department_name}} - {{$value->location_name}})</option>                
                           <?php endforeach;
                       endif; ?>
                   </select>
                   @if ($errors->has('role_id'))
                   <span class="help-block">
                    <strong>{{ $errors->first('role_id') }}</strong>
                </span>
                @endif
              </div>
            </div>
             <div class="col-md-3">
                        <label class="control-label">Designation<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('designation') ? ' has-error' : '' }}">
                            <input type="designation" id="designation" required name="designation" class="form-control" placeholder="Designation" <?php if(isset($users->designation ) && !empty($users->designation)){?> value="{{ $users->designation}}"
                            <?php } else { ?> value="{{old('designation')}}" <?php } ?> >
                            @if ($errors->has('designation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('designation') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                  </div>
                   <!--  <div class="form-group"> -->
                    <!-- <div class="col-md-6">
                        <label class="control-label">Phone Number<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                            <input type="text"  name="phone_number" pattern="[0-9]{10}" class="form-control numberonly" placeholder="Phone Number" <?php if(isset($users->phone_number ) && !empty($users->phone_number)){?> value="{{ $users->phone_number}}"
                            <?php } else { ?> value="{{old('phone_number')}}" <?php } ?> >
                            @if ($errors->has('phone_number'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div> -->
                
           <!--   </div>    -->
                   @if(isset($is_update_url) && $is_update_url == 0)
                    <div class="form-group">

                      <div class="col-md-6">
                        <label class="control-label">Password<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" value="{{old('password')}}">
                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                      </div>  
                        <div class="col-md-6">
                        <label class="control-label">Confirm Password<span class="e-color">*</span></label>
                        <div class="">
                            <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                        </div>
                       </div> 
                    </div>
                    @endif
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-5">
                                <button class="btn btn-primary" type="Submit"  onClick="encrypt()">Submit</button>
                            </div>
                        </div>    
                </form>
            </div>
        </div> 
    </div>      
<!--     <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-5">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </div>    
</form> -->
</div>
<!-- </div>
</div>    
</div> -->
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/crypto-js.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/enc-base64.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/sha1.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/aes.js" type="text/javascript"></script>

<script type="text/javascript">
   $(".chosen-select").chosen({width: "100%"});

     function encrypt(){

        const passcode = document.getElementById('password').value; 
        const passcode_confirm = document.getElementById('password_confirmation').value;
        if(passcode != "" && passcode_confirm!= "")  
        {
          var sha1Hash = CryptoJS.SHA1(passcode);
          var sha1HashToBase64 = sha1Hash.toString(CryptoJS.enc.Base64);
          document.getElementById('password').value = sha1HashToBase64;

                  
          var sha1Hash_confirm = CryptoJS.SHA1(passcode_confirm);
          var sha1HashToBase64_confirm = sha1Hash_confirm.toString(CryptoJS.enc.Base64);
          document.getElementById('password_confirmation').value = sha1HashToBase64_confirm;
        }
    
      }
   /*$("form").submit(function(){
   

       $("#password").attr("type", "password");
       $("#password_confirmation").attr("type", "password");
          
     var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

       var pass = Base64.encode($("#password").val());
       var confirm_pass = Base64.encode($("#password_confirmation").val());
       
       $("#password").val(pass);
       $("#password_confirmation").val(confirm_pass);
       
   });*/
</script> 
@endsection            
