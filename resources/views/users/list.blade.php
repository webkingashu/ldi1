@extends('layouts.app')
@section('title', 'Users')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
@endsection
<?php 
$users = getUserDetails(auth()->user()->id);
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
              
            <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong> Users</strong></h3>
                <div class="ibox-tools col-md-2">
                    <a href="{{ url('/register') }}" title="Add New User"><button class="btn btn-primary dim" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </a>
                </div>
            </div>

            <div class="ibox-content">
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
            <div class="table-responsive">
             <table class="table table-bordered dataTables-example">
                <thead>
                    <tr>            
                        <th>Sr.No.</th>
                        <th>User Name</th>
                        <th>Email </th>
                       
                        <th>Role </th>
                        <th>Action</th>
                        <!-- @role('Developer')
                        <th>Status</th>
                        @endrole -->
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1;?>
                    @foreach($data as $key => $value)
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$value['name']}}</td>
                        <td>{{$value['email']}}</td>
                      
                        <td>{{$value['role']}}</td>
                        <td class="row-actions">
                            <a href="{{ url('/users/'.$value['id'].'/edit')}}"><button class="btn btn-info btn-sm action-button" title="Edit User">Edit</button></a>
                             <!-- <form method="POST" action="{{ url('/users' . '/' . $value['id']) }}" accept-charset="UTF-8" style="display:inline">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm action-button" title="Delete User" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </form> -->
                            @if(isset($users['entities'])&& !empty($users['entities']) &&  in_array('user',$users['entities'])) 
                            <button data-id="{{$value['id']}}" class="btn btn-warning btn-sm action-button changePass" title="Change Password">Change Password</button>
                             <form method="POST" action="{{ url('/users/change-status' . '/' . $value['id']) }}" accept-charset="UTF-8" style="display:inline">
                                {{ csrf_field() }}
                                <input type="hidden" name="user_status" value="{{isset($value['user_status'])?$value['user_status']:''}}"/>
                                <button class="btn btn-primary btn-sm action-button" title="Enable" 
                                <?php if(isset($value['user_status']) && $value['user_status'] == 'Enable'){?> style="display:none;" <?php }else { ?> <?php } ?>>Enable</button>
                                <button class="btn btn-danger btn-sm action-button" title="Disable" <?php if(isset($value['user_status']) && $value['user_status'] == 'Disable'){?> style="display:none" <?php }else { ?> <?php } ?>>Disable</button>
                            </form>
                            @endif
                           
                        </td>
                    
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>   
          <a data-toggle="modal" href="#add_modal_change_pass"></a>
          <div  class="modal fade" aria-hidden="true" id="add_modal_change_pass">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-body">
                          <div class="row">
                              <div class="col-sm-12">
                                  <div class="text-center">
                                      <h3 class="m-t-none m-b">Reset Password</h3>
                                  </div>
                    <form method="POST" action="{{ url('/reset-password') }}">  
                         {{ csrf_field() }}    
                       <div class="col-md-6">
                        <input type="hidden" name="user_id" value="">
                        <label class="control-label">Password<span class="e-color">*</span></label>
                        <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input type="password" id="pass" name="pass" class="form-control" placeholder="Password" required>
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
                            <input type="password" class="form-control" placeholder="Confirm Password" id="pass_confirmation" name="pass_confirmation" required>
                        </div>
                       </div> 
                            
                          <div class="form-group" > 
                               
                                <input class="btn btn-primary btn btn-primary col-md-offset-4" type="Submit" onClick="encrypt()" value="Submit" style="margin-top: 20px;">
                                 <input class="btn btn-primary closeModal" type="reset"  value="Cancel" style="margin-top: 20px;">
                           
                          </div>
                         </form>   
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
</div>
@endsection

@section('scripts')
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/crypto-js.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/enc-base64.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/sha1.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/crypto-js@3.1.9-1/aes.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
        //     { extend: 'copy'},
        //     {extend: 'csv'},
        //     {extend: 'excel', title: 'ExampleFile'},
        //     {extend: 'pdf', title: 'ExampleFile'},

        //     {extend: 'print',
        //     customize: function (win){
        //         $(win.document.body).addClass('white-bg');
        //         $(win.document.body).css('font-size', '10px');

        //         $(win.document.body).find('table')
        //         .addClass('compact')
        //         .css('font-size', 'inherit');
        //     }
        // }
        ]

    });

    });

  $(".changePass").on("click", function () {
  $(".select2").select2({
            width: '100%'
        })  
   var id = $(this).data('id');
   //alert(id);
   $('input[name="user_id"]').val(id);
   $('#add_modal_change_pass').modal('show');
   }); 

   function encrypt(){

        const passcode = document.getElementById('pass').value;
        const passcode_confirm = document.getElementById('pass_confirmation').value;

        if (passcode !="" && passcode_confirm !="") {       
        var sha1Hash = CryptoJS.SHA1(passcode);
        var sha1HashToBase64 = sha1Hash.toString(CryptoJS.enc.Base64);
        document.getElementById('pass').value = sha1HashToBase64;

                
        var sha1Hash_confirm = CryptoJS.SHA1(passcode_confirm);
        var sha1HashToBase64_confirm = sha1Hash_confirm.toString(CryptoJS.enc.Base64);
        document.getElementById('pass_confirmation').value = sha1HashToBase64_confirm;
        } 
        else
        {
            alert("Password is required");
        }
      }
 
</script>     
@endsection 