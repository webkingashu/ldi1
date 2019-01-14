@extends('layouts.app')
@section('title', 'Release Order')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/sweetalert2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
               <div class="ibox-title col-md-12 display-flex">
                <h3 class="col-md-10"><strong>Release Order</strong></h3>
                <div class="ibox-tools col-md-4">

                        @if(isset($transaction_data) && !empty($transaction_data))
                        <?php   $count = 0; ?>
                            @foreach($transaction_data as $value) 
                                @if($count == 0 && $value['to_status_name'] == "Pending Approval")     
                                <a href="{{ url('/ro/' . $releaseOrder->id . '/edit') }}" title="Back"><button class="btn btn-primary dim"><i  aria-hidden="true"></i>Edit</button></a>
                                @endif
                            @endforeach
                        @endif
                  
                    <a href="{{ url('/revision/' . $releaseOrder->id. '/' . $entity_details->entity_slug) }}" title="Revision History"><button class="btn btn-primary dim">Revision History</button></a>
                    <a href="{{ url('ro') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>
                </div>
            </div>
            <div class="ibox-title col-md-12 display-flex">
                    <ul class="breadcrumb">
                        <li><a class="breadcrumb-item" href="{{ url('/ro') }}">Release Order</a></li>
                        <li><a class="breadcrumb-item">View Release Order</a></li>
                    </ul>
            </div>
                <!-- <div class="ibox-title">
                    <h5><a href="{{ url('/purchase-order') }}" title="Back"><button class="btn btn-primary btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a><strong>Purchase Order</strong></h5>
                </div> -->

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
                            <!-- <tr>
                                <th>ID</th><td>{{ $releaseOrder->id }}</td>
                            </tr> -->
                            <tr>
                                <th> Release Order Title</th><td> {{ $releaseOrder->ro_title }} </td>
                                <th> EAS Name </th><td> {{ $releaseOrder->sanction_title }} </td>
                                
                            </tr>
                            <tr>
                                <th> Vendor Name </th><td> {{ $releaseOrder->vendor_name }} </td>
                                <th> Vendor Contact Number </th><td> {{ $releaseOrder->mobile_no }} </td>
                            </tr>
                            <tr>
                                <th> Bank Name</th><td> {{ $releaseOrder->bank_name }} </td>
                                <th>Branch Name</th><td> {{ $releaseOrder->bank_branch }} </td>
                            </tr>
                            
                            <tr>
                             <th> IFSC Code</th><td> {{ $releaseOrder->ifsc_code }} </td>
                             <th> Budget Code</th><td> {{ $releaseOrder->budget_code }} </td>
                         </tr>
                         <tr>
                             <th> Total Sanctioned amount</th><td> {{ $releaseOrder->sanction_total }} </td>
                             <th> Bank Code</th><td> {{ $releaseOrder->bank_code }} </td>
                         </tr>
                         <tr>
                             <th> Current/ Cash Credit Account No</th><td> {{ $releaseOrder->bank_acc_no }} </td>
                             <th> Release Order amount</th><td> {{ $releaseOrder->release_order_amount }} </td>
                         </tr>
                         <tr>
                             <th> Status</th> <!-- <td> {{ $releaseOrder->status_name }} </td> -->
                              <td> @if($releaseOrder->status_name == "Approved")
                            <span class="label label-primary"> {{ $releaseOrder->status_name }} </span>
                              @elseif ($releaseOrder->status_name == "Pending Approval")
                                  <span class="label label-warning"> {{ $releaseOrder->status_name}}</span>
                              @elseif ($releaseOrder->status_name == "Draft")
                                  <span class="label label-info">  {{ $releaseOrder->status_name}}</span>
                              @elseif ($releaseOrder->status_name == "Return" || $releaseOrder->status_name == "DDO Return" || $releaseOrder->status_name == "PAO Return")
                                  <span class="label label-danger">{{ $releaseOrder->status_name}}</span>
                              @elseif ($releaseOrder->status_name == "DDO Approved" || $releaseOrder->status_name == "PAO Approved")
                                  <span class="label label-success">{{ $releaseOrder->status_name}}</span>
                              @else
                                  <span class="label">{{ $releaseOrder->status_name }}</span>
                              @endif
                          </td>
                             @if(isset($releaseOrder) && !empty($releaseOrder->ro_pdf) && isset($releaseOrder->status_id) &&  isset($entity_details->final_status) && ($releaseOrder->status_id) == $entity_details->final_status) 

                             <th>Generated Release Order</th><td><a class="btn btn-danger btn-sm" href="{{url('/download-ro_pdf')}}/<?php echo $releaseOrder->id; ?>" >Download</a></td>
                             @endif
                         </tr>
                           <tr>
                               <th>Assignee</th><td> {{ isset($releaseOrder->assignee) ? $releaseOrder->assignee : '-' }} </td>
                            </tr>


                     </tbody>
                 </table>    
             </div>
             <div class="hr-line-dashed"></div>   
                @include('transactions_view') 
 @if(isset($invoice_details) && !empty($invoice_details))             
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Invoice Details</h5>
                            <div class="ibox-tools col-md-2">
                            
                          </div>
                        </div>
                        <div class="ibox-content">
                            
                            <table class="table table-bordered" id="previous_table">
                                <thead>
                                <tr>
                                  <th>Sr.No</th>
                                  <th>Invoice No.</th> 
                                  <th>Agency Name</th>
                                  <th>Qty</th> 
                                  <th>Period</th>
                                  <th>Amount sanctioned for payment (Rs)</th>
                                  <th>SLA Penalty Amount /Liquidated damages (Rs.)</th>
                                  <th>Taxes as applicable</th>
                                  <th>Withheld amount</th>
                                  <th>Net payable amount (Rs.)</th>
                                
                              </tr>
                                </thead>
                                 <tbody>
                               
                                <?php $count = 1;?>
                                  @foreach($invoice_details as $key =>$val)
                                 
                                     <tr class="field_group">
                                      <td class="sr_no">{{$count++}}</td>
                                      <td>{{isset($val['invoice_no'])?$val['invoice_no']:''}}</td>
                                      <td>{{isset($val['agency_name'])?$val['agency_name']:''}}</td>
                                      <td>{{isset($val['qty'])?$val['qty']:''}}</td>
                                      <td>{{isset($val['period'])?$val['period']:''}}</td>
                                      <td>{{isset($val['amount_payment'])?$val['amount_payment']:''}}</td>
                                      <td>{{isset($val['sla_amount'])? $val['sla_amount']:''}}</td>
                                      <td>{{isset($val['applicable_taxes'])?$val['applicable_taxes']:''}}</td>
                                       <td>{{isset($val['withheld_amount'])?$val['withheld_amount']:''}}</td>
                                       <td><span class="net_payable_amount">{{isset($val['net_payable_amount'])?$val['net_payable_amount']:''}}</td>
                                     
                                  </tr>
                                 
                                  @endforeach
                                </tbody> 
                                 <tfoot>
                                  <tr>
                                  
                                     <td colspan="9" style="text-align: right;"><strong>Total</strong></td>
                                     <td><span class="total_payable_amount"></td>
                                    
                                </tr> 
                               
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
               </div>
          </div>
          @endif
             <div class="hr-line-dashed"></div>

          
    </div>
</div>
</div>
</div>
</div>                      
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/sweetalert2.all.min.js') !!}"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script type="text/javascript">
  $(document).ready(function () {
calNetPayableAmount();
    });
    function calNetPayableAmount() {

   var counttoll = 0;
         $(".net_payable_amount").each(function () {
 
       if (!isNaN(parseFloat($(this).text()))) {
           counttoll = parseFloat(parseFloat(counttoll || 0) + parseFloat($(this).text()));
       }
       });

     $(".total_payable_amount").text(counttoll);

}
$(".start").click(function() {
    var created_by = '{{isset($releaseOrder->created_by)?$releaseOrder->created_by:''}}';
    var transaction_id = this.id;
    var entity_id =  '{{isset($entity_details->id)?$entity_details->id:''}}';
    var entity_slug =  '{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}';
    var workflow_id ='{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}';
    var url = '{{url("/")}}/updateCurrentTransaction';
    var id = '{{isset($releaseOrder->id)?$releaseOrder->id:''}}';
    var status_name = $(this).val();   
    var privious_status = $(this).data('privious_status');            
    var vendor_name = '{{isset($releaseOrder->vendor_name) ? $releaseOrder->vendor_name:''}}';   
    var sanction_title = '{{isset($releaseOrder->sanction_title)? $releaseOrder->sanction_title:''}}';
    var ro_title = '{{isset($releaseOrder->ro_title)? $releaseOrder->ro_title:''}}';
    var release_order_amount = '{{isset($releaseOrder->release_order_amount)?$releaseOrder->release_order_amount:''}}';
    var final_status= '{{isset($entity_details->final_status)?$entity_details->final_status:''}}';
    var old_assignee = '{{isset($assigne->old_assignee) ? $assigne->old_assignee:''}}';
    var array = '<?php print_r($users_json);?>';
    var options = {};
    obj = JSON.parse(array);
    $.map(obj, function (o) {
        options[o.id] = o.name;
    });
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

              if(status_name == "Return" || status_name == "DDO Return" || status_name == "AAO Return" || status_name == "PAO Return")  {
              comment(assignee=null);
              } 
              else if(status_name == "PAO Approve" )  {
                updateTransaction(inputValue=null,assignee=null)
              }
              else {
                assignee();
              }
          }
        });

    function assignee(){
       swal({
      title: 'Do you want to assign?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm!',
        showCancelButton: true,
    }).then(function(result) {
      if(result.value){
      swal({
        title: 'Select Assignee',
        input: 'select',
        inputOptions: options,
        inputPlaceholder: 'Select Assignee',
        showCancelButton: true,
        inputValidator: function (assignee) {
           if (assignee != '') {
            return new Promise(function (resolve, reject) {
              if(status_name == "Return")  {
                comment(assignee);
             } else {
              updateTransaction(inputValue=null,assignee)
             }
            })
          
          } else {
            return !assignee && 'Please Select Assignee.';
          }   
        }
      })
      } else {
        if(status_name == "Return")  {
              comment(assignee=null);
            } else {
              updateTransaction(inputValue=null)
             }
      }
})
}
    function comment(assignee){
      
      swal({
                 title: "Please Enter Comment!",
                  text: "Why this Release Order Rejected ?",
                  input: 'text',                  
                  showCancelButton: true,
                  inputPlaceholder: "Enter comment",
                inputValidator: function (inputValue) {
                 
                    if (inputValue != '') {
                      return new Promise(function (resolve, reject) {
                      updateTransaction(inputValue,assignee)
                  
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
                        entity_id :entity_id,comment:inputValue,assignee:assignee,old_assignee:old_assignee,
                        workflow_id:workflow_id,created_by:created_by,entity_slug:entity_slug,id:id,status_name:status_name,ro_title:ro_title,vendor_name:vendor_name,privious_status:privious_status,release_order_amount:release_order_amount,sanction_title:sanction_title,final_status:final_status,
                       _token: "{{csrf_token()}}"
                    },
                     
                    success: function(data) {

                       console.log(data);
                      // alert(data)
                      if(data.code == 204){
                        swal({
                            title: 'Status', 
                            text: data.message,
                            type: 'error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                      } else {
                        swal({
                                title: 'Updated!', 
                                text: data.message,
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1000
                            });
                             location.reload();
                         if(data.status_id == final_status) {
                         
                            swal({
                                title: "PAO Approved",
                                text: data.message,
                                type: "success",
                                showConfirmButton: false,
                                timer: 1000
                            }); 
                               window.location.href = "{{ url('/ro') }}/"+id;
                            
                        } else {
                            swal({
                                title: 'Updated!', 
                                text: data.message,
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1000
                            });
                             location.reload();
                          }
                        }
                    },
                   complete: function(){
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
//});
</script>      
@endsection 
