@extends('layouts.app')
@section('title', 'Manage Profile')

@section('css')
<link rel="stylesheet" href="{!! asset('css/animate.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/style.css') !!}" />


<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
@endsection
@section('content')
<div class="wrapper wrapper-content">
  <div class="row animated fadeInRight">

   <div class="col-md-4">
    <div class="ibox float-e-margins">

      <div class="ibox-title">
        <h5> <a  class="text-primary" href="{{ url('user') }}"><i  class="fa fa-lg fa-arrow-left"></i></a> Profile Detail</h5>
      </div>
      <div>
        <div class="ibox-content no-padding border-left-right">
          <img alt="image" class="img-responsive" src="{!! asset('img/manage-profile.jpg') !!}">
        </div>
        <div class="ibox-content profile-content">
          <h4>About me</h4>
          <h5><strong>Name : </strong>{{Auth::user()->name}}</h5>
          <h5><strong>Email : </strong>{{Auth::user()->email}}</h5>
        </div>
      </div>
    </div>
  </div>


  <div class="col-md-8">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#manage_profile">Manage Profile</a></li>
          <li><a data-toggle="tab" href="#manage_password">Manage Password</a></li>
        </ul>
        <div class="tab-content">
          <div id="manage_profile" class="tab-pane fade in active">
            <div  class="ibox-content">
              <div  class="panel">
                <div  class="panel-body">
                  <div class="row">
                    <form class="m-t" role="form" method="POST" action="{{ url('update/' . $manage_profile->id) }}" name="manage_profile">
                     {{ method_field('PATCH') }}
                     {{ csrf_field() }}

                     <div class="form-group col-md-6">
                      <label>Full Name</label> 
                      <input type="text"  autofocus maxlength="50" name="name" required placeholder="Enter Name" class="form-control" <?php if (isset($manage_profile->name) && !empty($manage_profile->name)) {?>
                        value="{{ $manage_profile->name}} "
                      <?php } else {?>
                        value="{{ old('name') }}" <?php }?> >
                      </div>
                      <div class="form-group col-md-6 {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label>Email</label> 
                        <input type="email" maxlength="30" name="email" pattern=".+@uidai.net.in" readonly required="required" placeholder="Enter email abc@uidai.net.in" class="form-control"  <?php if (isset($manage_profile->email) && !empty($manage_profile->email)) {?>
                        value="{{ $manage_profile->email}} "
                      <?php } else {?>
                        value="{{ old('email') }}" <?php }?> >

                       @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif</div>


                        <div class="form-group col-md-6">

                          <label for="role" class="control-label">Role<span class="e-color">*</span></label>
                          <div class="{{ $errors->has('vendor_id') ? 'has-error' : ''}}">
                            <select disabled="" class="form-control chosen-select" name="role" id="role">      
                              <option disabled="disabled" selected>Select Role</option>
                              @if(isset($roles) && count($roles) > 0)
                              @foreach($roles as $value) 
                              <option <?php if(isset($value->id) && isset($manage_profile->role_id) && $manage_profile->role_id== $value->id) { echo 'selected';} ?> value="{{ $value->id }}">{{$value->display_name}}</option>
                              @endforeach
                              @endif
                            </select>
                            {!! $errors->first('vendor_id', '<p class="help-block">:message</p>') !!}
                          </div>
                        </div>
                        <div class="col-md-12 pull-right">
                          <a class="btn btn-primary pull-right" href="{{ url('manage-profile') }}"  tabindex="5">Cancel</a>
                          <button class="btn btn-primary pull-right cancel_btn_css" type="submit" tabindex="4">Update</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="manage_password" class="tab-pane fade">
              <div  class="ibox-content">
               <div  class="panel">
                <div  class="panel-body">
                  <div class="row">
                <!--   {!! Form::open(array('action' => array('ManageProfileController@change_password'),'name'=>'manage_pass','id'=>'manage_pass'))!!}
                  {{ csrf_field() }} -->

                  <form class="m-t" role="form" method="POST" action="{{ url('change_password') }}" name="manage_pass">
                    {{ csrf_field() }}
                    <div class="row" id="pwd-container1" >
                        <div class="form-group col-md-6" >
                          <label>Old Password</label> 
                          <input type="password" name="password" id="old_password" maxlength="20"  autofocus placeholder="Enter Password" class="form-control old_password " required>
                          <span toggle="#old_password" class="fa fa-fw fa-eye field-icon toggle-old-password"> </span>
                        </div>
                          <div class="form-group col-md-4">
                            <div class="pwstrength_viewport_progress" style="margin-top: 27px;"></div>
                          </div>
                    </div>

                    <div class="row" id="pwd-container2" >
                    <div class="form-group col-md-6 ">
                      <label>New Password</label> 
                      <input type="password" name="new_password" id="new_password" maxlength="20"  placeholder="Enter New Password" class="new_password form-control enc_password" required>
                      <span toggle="#new_password" class="fa fa-fw fa-eye field-icon toggle-new-password"> </span>
                    </div>
                    <div class="form-group col-md-4">
                            <div class="pwstrength_viewport_progress" style="margin-top: 27px;"></div>
                          </div>
                    </div>


                    <div class="row" id="pwd-container3" >
                    <div class="form-group col-md-6"  >
                      <label>Confirm Password</label> 
                      <input type="password" name="confirm_password" id="confirm_password" maxlength="20"  placeholder="Enter Confirm Password" id="password" class="form-control enc_password cnew_password" required>
                      
                      <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-confirm-password"> </span>
                    </div>
                     <div class="form-group col-md-4">
                            <div class="pwstrength_viewport_progress" style="margin-top: 27px;"></div>
                          </div>
                    </div>

                  </div>
                  <div class="col-md-12 pull-right">
                    <a class="btn btn-primary pull-right" href="{{ url('manage_profile') }}"  tabindex="5">Cancel</a>
                    <button class="btn btn-primary pull-right cancel_btn_css" type="submit" tabindex="4">Save</button>
                  </div>
                </form>
                <!-- {!! Form::close() !!} -->
              </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>
</div>
</div>
</div>

@endsection

@section('scripts')
<script src="{!! asset('js/jquery.validate.min.js') !!}"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/pwstrength/pwstrength-bootstrap.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/pwstrength/zxcvbn.js') !!}" type="text/javascript"></script>



<script type="text/javascript">

  $('.chosen-select').chosen({width: "100%"});
  $(document).ready(function(){

   var options1 = {};
   options1.ui = {
    container: "#pwd-container1",
    showVerdictsInsideProgressBar: true,
    viewports: {
      progress: ".pwstrength_viewport_progress"
    }
  };
  options1.common = {
    debug: false,
  };
  $('.old_password').pwstrength(options1);


   var options2 = {};
   options2.ui = {
    container: "#pwd-container2",
    showVerdictsInsideProgressBar: true,
    viewports: {
      progress: ".pwstrength_viewport_progress"
    }
  };
  options2.common = {
    debug: false,
  };
  $('.new_password').pwstrength(options2);

   var options3 = {};
   options3.ui = {
    container: "#pwd-container3",
    showVerdictsInsideProgressBar: true,
    viewports: {
      progress: ".pwstrength_viewport_progress"
    }
  };
  options3.common = {
    debug: false,
  };
  $('.cnew_password').pwstrength(options3);

});
   // Wait for the DOM to be ready
   $(function () {
       // It has the name attribute "registration"
       $("form[name='manage_profile']").validate({
           // Specify validation rules
           rules: {
            name: "required",
            email: "required",
            role: "required",
          },
           // Specify validation error messages
           messages: {
            name: "Please enter your Name",
            email: "Please enter your Email",
            role: "Please select your Role",
          },
           // Make sure the form is submitted to the destination defined
           // in the "action" attribute of the form when valid
           submitHandler: function (form) {

             form.submit();
           }
         });
     });


   $(function () {
      // It has the name attribute "registration"
      $("form[name='manage_pass']").validate({
          // Specify validation rules
          rules: {

            old_password: "required",
            new_password: "required",
            confirm_password : {
              required: true,
              equalTo : "#new_password"
            }
          },
          // Specify validation error messages
          messages: {
            old_password: "Please enter old password",
            new_password: "Please enter new password",
            password: "Please enter password",
            confirm_password:{
             required: "Please enter confirm password",
             equalTo: "Password and Confirm Password should be same"
           }
         },
          // Make sure the form is submitted to the destination defined
          // in the "action" attribute of the form when valid
          

          submitHandler: function (form) {

           $("#old_password").attr("type", "password");
           $("#new_password").attr("type", "password");
           $("#confirm_password").attr("type", "password");
           
           var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

           var old_password = Base64.encode($("#old_password").val());
           $("#old_password").val(old_password);

           var new_password = Base64.encode($("#new_password").val());
           $("#new_password").val(new_password);

           var confirm_password = Base64.encode($("#confirm_password").val());
           $("#confirm_password").val(confirm_password);
           form.submit();
         }
       });
    });

   $(".toggle-new-password").click(function() {
     $(this).toggleClass("fa-eye fa-eye-slash");
     var input = $($(this).attr("toggle"));
     if (input.attr("type") == "password") {
       input.attr("type", "text");
     } else {
       input.attr("type", "password");
     }
   });


   $(".toggle-old-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });


   $(".toggle-confirm-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });


</script>
@endsection
