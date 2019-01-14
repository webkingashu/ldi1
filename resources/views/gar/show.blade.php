@extends('layouts.app')
@section('title', 'GAR')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/iCheck/custom.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="{!! asset('css/sweetalert2.all.min.css') !!}"></script>
<link rel="stylesheet" href="{!! asset('css/plugins/dataTables/datatables.min.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong>GAR</strong></h3>
                    <div class="ibox-tools col-md-4">
                      
                        @if(isset($transaction_data) && !empty($transaction_data))
                        <?php   $count = 0; ?>
                            @foreach($transaction_data as $value) 
                                @if($count == 0 && $value['to_status_name'] == "Pending Approval" || $value['to_status_name'] == "Dispatch And Tally Entry Done")     
                                <a href="{{ url('/gar/' . $gar_details->id . '/edit') }}" title="Back"><button class="btn btn-primary dim"><i  aria-hidden="true"></i>Edit</button></a>
                                @endif
                            @endforeach
                        @endif
                  
                    <a href="{{ url('/revision/' . $gar_details->id. '/' . $entity_details->entity_slug) }}" title="Revision History"><button class="btn btn-primary dim">Revision History</button></a>
                        <a href="{{ url('gar') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                    </div>
                  </div>
                   <div class="ibox-title col-md-12 display-flex">
                    <ul class="breadcrumb">
                        <li><a class="breadcrumb-item" href="{{ url('/eas') }}">GAR</a></li>
                        <li><a class="breadcrumb-item">View GAR</a></li>
                    </ul>
                  </div>
            <div class="ibox-content form-horizontal">
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
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                             <tbody>
                        
                            <tr>
                                <th> EAS Title</th><td> {{ $gar_details->sanction_title }} </td>
                                <th> Ro Title </th><td> {{ $gar_details->ro_title }} </td>
                                
                            </tr>
                            <tr>
                                <th> Vendor Name </th><td> {{ $gar_details->vendor_name }} </td>
                                <th> Vendor Contact Number </th><td> {{ $gar_details->mobile_no }} </td>
                            </tr>
                            <tr>
                                <th> Bank Name</th><td> {{ $gar_details->bank_name }} </td>
                                <th>Branch Name</th><td> {{ $gar_details->bank_branch }} </td>
                            </tr>
                            <tr>
                               <th> IFSC Code</th><td> {{ $gar_details->ifsc_code }} </td>
                               <th> Budget Code</th><td> {{ $gar_details->budget_code }} </td>
                            </tr>
                            <tr>
                               <th> Total Release Order Amount</th><td> {{ $gar_details->release_order_amount }} </td>
                               <th> Bank Code</th><td> {{ $gar_details->bank_code }} </td>
                            </tr>
                             <tr>
                               <th>GST Amount</th><td> {{ $gar_details->gst_amount }} </td>
                               <th>TDS Amount</th><td> {{ $gar_details->tds_amount }} </td>
                            </tr>

                            <tr>
                               <th>TDS On GST Amount</th><td> {{ $gar_details->gst_tds_amount }} </td>
                               <th>LD Or Penalty Amount</th><td> {{ $gar_details->ld_amount }} </td>
                            </tr>

                            <tr>
                               <th>Withheld Amount</th><td> {{ $gar_details->with_held_amount }} </td>
                               <th>Other Amount</th><td> {{ $gar_details->other_amount }} </td>
                            </tr>
                             <tr>
                               <th> Current/ Cash Credit Account No</th><td> {{ $gar_details->bank_acc_no }} </td>
                               <th> Release Order amount</th><td> {{ $gar_details->release_order_amount }} </td>
                            </tr>
                           
                            <tr>
                               <th> Status</th><td>  @if($gar_details->status_name == "Approved" || $gar_details->status_name == "DDO Approved" || $gar_details->status_name == "PAO Approved" || $gar_details->status_name == "Forwarding Letter Generated")
                                   <span class="label label-primary"> {{ $gar_details->status_name }} </span>
                                   @elseif ($gar_details->status_name == "Pending Approval")
                                   <span class="label label-warning">  {{ $gar_details->status_name}}</span>
                                   @elseif ($gar_details->status_name == "Draft" || $gar_details->status_name == "Dispatch And Tally Entry Done" || $gar_details->status_name == "Cheque Uploaded" )
                                   <span class="label label-info">{{ $gar_details->status_name}}</span>
                                   @elseif ($gar_details->status_name == "Return" || $gar_details->status_name == "DDO Return" || $gar_details->status_name == "PAO Return")
                                   <span class="label label-danger">{{ $gar_details->status_name}}</span>
                                   @else
                                   <span class="label">{{ $gar_details->status_name }}</span>
                                   @endif</td>
                           @if(isset($gar_details) && !empty($gar_details->gar_pdf) && isset($gar_details->status_id) &&  isset($entity_details->final_status) && ($gar_details->status_id) == $entity_details->final_status) 
                                    <th>Generated Gar</th><td><a class="btn btn-danger btn-sm" href="{{url('/download-gar/' .$gar_details->id)}}">Download</a></td>
                                     @endif

                               
                            </tr>
                        </tbody>
                        </table>    
                    </div>
                    <div class="hr-line-dashed"></div> 
                @include('transactions_view')
               
            </div>
        </div>
     </div>
   </div>
</div>                      
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/dataTables/datatables.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script src="{!! asset('js/plugins/iCheck/icheck.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/sweetalert2.all.min.js') !!}"></script>
<script type="text/javascript">
$(".start").click(function() {
  var created_by = '{{isset($gar_details->created_by)?$gar_details->created_by:''}}';
  var transaction_id = this.id;
  var entity_id =  '{{isset($entity_details->id)?$entity_details->id:''}}';
  var entity_slug =  '{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}';
  var workflow_id ='{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}';
  var url = '{{url("/")}}/updateCurrentTransaction';
  var id = '{{isset($gar_details->id)?$gar_details->id:''}}';
  var status_name = $(this).val();   
  var privious_status = $(this).data('privious_status');    
  var file_number = '{{isset($gar_details->file_number)?$gar_details->file_number:''}}';
  var vendor_name = '{{isset($gar_details->vendor_name)?$gar_details->vendor_name:''}}';
  var actual_payment_amount = '{{isset($gar_details->actual_payment_amount)?$gar_details->actual_payment_amount:''}}';
  var sanction_title = '{{isset($gar_details->sanction_title)?$gar_details->sanction_title:''}}';
  var email_users = '{{isset($gar_details->email_users)?$gar_details->email_users:''}}';
  var gar_type = '{{isset($gar_details->gar_bill_name)?$gar_details->gar_bill_name:''}}';
  var final_status =  '{{isset($entity_details->final_status)?$entity_details->final_status:''}}';

  swal({
            title: "Are you sure want to change status?",
            text: "Once status changed,will not retain again!!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm!',
            showCancelButton: true,
        }).then(function(result) {
          if(result.value) {
            //assignee();
             if(status_name == "Return" || status_name == "DDO Return" || status_name == "AAO Return" || status_name == "PAO Return")  {
                comment();
             } else {
              updateTransaction()
             }
          }
        });

//     function assignee(){
//        swal({
//       title: 'Are want to assigne?',
//         text: "You won't be able to revert this!",
//         type: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Confirm!',
//         showCancelButton: true,
//     }).then(function(result) {
//       if(result.value){
//       swal({
//         title: 'Select Assignee',
//         input: 'select',
//         inputOptions: options,
//         inputPlaceholder: 'Select Assignee',
//         showCancelButton: true,
//         inputValidator: function (assignee) {
//            if (assignee != '') {
//             return new Promise(function (resolve, reject) {
//               if(status_name == "Return" || status_name == "DDO Return" || status_name == "AAO Return" || status_name == "PAO Return")  {
//                 comment(assignee);
//              } else {
//               updateTransaction(inputValue=null,assignee)
//              }
//             })
          
//           } else {
//             return !assignee && 'Please Select Assignee.';
//           }   
//         }
//       })
//       } else {
//         if(status_name == "Return" || status_name == "DDO Return" || status_name == "PAO Return")  {
//               comment(assignee=null);
//             } else {
//               updateTransaction(inputValue=null)
//              }
//       }
// })
// }
    function comment(){
      swal({
                 title: "Please Enter Comment!",
                  text: "Why this GAR Rejected ?",
                  input: 'text',                  
                  showCancelButton: true,
                  inputPlaceholder: "Enter comment",
                inputValidator: function (inputValue) {
                 
                    if (inputValue != '') {
                      return new Promise(function (resolve, reject) {
                      updateTransaction(inputValue)
                  
                  })
                    } else {
                      return !inputValue && 'Please enter Comment.';
                    }

                  
                }
        })
    }


function updateTransaction(inputValue=null,assignee=null) {
    if(created_by && transaction_id && entity_id && workflow_id && url ) {

        $.ajax({
            url: url,
            dataType: "json",
            type:"POST",
            beforeSend: function(){
                swal.close()
                $('.div-loader').css("display", "block");
            },
            data: {
                transaction_type : transaction_id,
                entity_id :entity_id,comment:inputValue,email_users:email_users,workflow_id:workflow_id,created_by:created_by,entity_slug:entity_slug,id:id,status_name:status_name,privious_status:privious_status,file_number:file_number,sanction_title:sanction_title,vendor_name:vendor_name,actual_payment_amount:actual_payment_amount,gar_type:gar_type,final_status:final_status,
                _token: "{{csrf_token()}}"
            },

            success: function(data) {
                if(data.code == 204){
                    swal({
                        title: 'Status', 
                        text: data.message,
                        type: 'error',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                 //if(data.status_id == 33) {
                     if(data.status_id == final_status) {

                    swal({
                        title: "Approved",
                        text: data.message,
                        type: "success",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    window.location.href = "{{ url('/gar') }}/"+id;
                } else {

                    swal({
                        title: 'Updated!', 
                        text: data.message,
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload();
                }
            }
        },complete: function(){
          $('.div-loader').css("display", "none");
      }
  });
    } else {
        swal({
            title: 'Status', 
            text: 'Data Not Found.',
            type: 'message',
            showConfirmButton: false,
            timer: 1500
        });
    }
} 

 });
</script>       
@endsection            
