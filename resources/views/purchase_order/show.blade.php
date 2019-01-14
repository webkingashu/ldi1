@extends('layouts.app')
@section('title', 'Purchase Order Show')
@section('content')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/datepicker/datepicker3.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/chosen/bootstrap-chosen.css') !!}" />
<link rel="{!! asset('css/sweetalert2.all.min.css') !!}"></script>
<link href="{!! asset('css/plugins/jasny/jasny-bootstrap.min.css') !!}" rel="stylesheet">
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                 <div class="ibox-title col-md-12 display-flex">
                    <h3 class="col-md-10"><strong>Purchase Order</strong></h3>
                    <div class="ibox-tools col-md-4">

                            @if(isset($transaction_data) && !empty($transaction_data))
                            <?php   $count = 0;  ?>
                                @foreach($transaction_data as $value) 
                                    @if($count == 0 && $value['to_status_name'] == "Pending Approval")     
                                    <a href="{{ url('/purchase-order/' . $purchase_order_master->id . '/edit') }}" title="Back"><button class="btn btn-primary dim"><i  aria-hidden="true"></i>Edit</button></a>
                                    @endif
                                @endforeach
                            @endif
                        
                        <a href="{{ url('/revision/' . $purchase_order_master->id. '/' . $entity_details->entity_slug) }}" title="Revision History"><button class="btn btn-primary dim">Revision History</button></a>
                        <a href="{{ url('purchase-order') }}" title="Back"><button class="btn btn-primary dim"><i class="fa fa-arrow-left" aria-hidden="true"></i></button></a>


                    </div>
                  </div>
                  <div class="ibox-title col-md-12 display-flex">
                    <ul class="breadcrumb">
                        <li><a class="breadcrumb-item" href="{{ url('purchase-order') }}">Purchase Order</a></li>
                        <li><a class="breadcrumb-item">View Purchase Order</a></li>
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
                                <th>ID</th><td>{{ $purchase_order_master->id }}</td>
                            </tr> -->
                            <tr>
                                <th> Vendor Name </th><td> {{ $purchase_order_master->vendor_name }} </td>
                                <th> Vendor Address </th><td> {{ $purchase_order_master->vendor_address }} </td>
                            </tr>
                            <tr>
                                <th> Subject </th><td> {{ $purchase_order_master->subject }} </td>
                                <th> Bid Number </th><td> {{ $purchase_order_master->bid_number }} </td>
                            </tr>
                            <tr>
                                <th>Date Of Bid</th><td> {{ $purchase_order_master->date_of_bid }} </td>
                                <th>Title Of Bid</th><td> {{ $purchase_order_master->title_of_bid }} </td>
                            </tr>
                         
                            <tr>
                                
                                 @if(isset($purchase_order_master->status_name) && !empty($purchase_order_master->status_name) )
                                    <th>Status</th>
                                    <td>
                                       @if($purchase_order_master->status_name == "Approved")
                                       <span class="label label-primary"> {{ $purchase_order_master->status_name }} </span>
                                       @elseif ($purchase_order_master->status_name == "Pending Approval")
                                       <span class="label label-warning">  {{ $purchase_order_master->status_name}}</span>
                                       @elseif ($purchase_order_master->status_name == "Draft")
                                       <span class="label label-info">  {{ $purchase_order_master->status_name}}</span>
                                       @elseif ($purchase_order_master->status_name == "Return")
                                       <span class="label label-danger">{{ $purchase_order_master->status_name}}</span>
                                       @else
                                       <span class="label">{{ $purchase_order_master->status_name }}</span>
                                       @endif
                                   </td>
                                   @endif
                                      @if(isset($purchase_order_master) && !empty($purchase_order_master->po_pdf) && isset($purchase_order_master->status_id) &&  isset($entity_details->final_status) && ($purchase_order_master->status_id) == $entity_details->final_status) 
                                    <th>Generated Purchase Order</th><td><a class="btn btn-danger btn-sm" href="{{url('/download-po_pdf')}}/<?php echo $purchase_order_master->id; ?>">Download</a></td>
                                     @endif
                            </tr>
                            <tr>
                               <th>Assignee</th><td> {{ isset($purchase_order_master->assignee) ? $purchase_order_master->assignee : '-' }} </td>
                            </tr>
                         <!--    @if(isset($purchase_order_master->po_pdf) && !empty($purchase_order_master->po_pdf)) 
                            <tr>
                                <th>Generated Purchase Order</th><td><a class="label label-success" target="_blank" href="{{url('/')}}/<?php echo $purchase_order_master->po_pdf; ?>">View</a></td>
                            </tr>
                            @endif -->
                        </tbody>
                    </table>    
                </div> 
                <div class="hr-line-dashed"></div> 
                @include('transactions_view')

                 @if(isset($item_details) && !empty($item_details))             
                <div class="wrapper wrapper-content animated fadeInRight" id="edit_item">
                  <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Item Details</h5>
                            <div class="ibox-tools col-md-2">
                            
                          </div>
                        </div>
                        <div class="ibox-content">
                            
                            <table class="table table-bordered" id="previous_table">
                                <thead>
                                <tr>
                                  <th>Sr.No</th>
                                  <th>Category</th> 
                                  <th>Item</th>
                                  <th>Qty</th> 
                                  <th>Unit Price Excl Tax</th>
                                  <th>Total Price Excl Tax</th>
                                  
                              </tr>
                                </thead>
                                 <tbody>
                               
                                <?php $count = 1;?>
                                  @foreach($item_details as $key =>$val)
                                 
                                     <tr class="field_group">
                                     <td class="sr_no">{{$count++}}</td>
                                      <td>{{isset($val['category'])?$val['category']:''}}</td>
                                      <td>{{isset($val['item'])?$val['item']:''}}</td>
                                      <td class="qty">{{isset($val['qty'])?$val['qty']:''}}</td>
                                      <td class="unit_price_tax">{{isset($val['unit_price_tax'])?$val['unit_price_tax']:''}}</td>
                                     <td><span class="total_unit_price_tax">{{isset($val['total_unit_price_tax'])?$val['total_unit_price_tax']:''}}</td>
                                  </tr>
                                  @endforeach
                                </tbody> 
                                 <tfoot>
                                  <tr>
                                     <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
                                     <td><span id="total_price_tax"></td>
                                </tr> 
                               
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
               </div>
          </div>
          @endif
                 
            </div>
        </div>
    </div>
</div>
</div>                      
@endsection
@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/datepicker/bootstrap-datepicker.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/chosen/chosen.jquery.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/sweetalert2.all.min.js') !!}"></script>
<script src="{!! asset('js/plugins/jasny/jasny-bootstrap.min.js') !!}"></script>
<script>
   $(document).ready(function () {

      multInputs();
    });
   function multInputs() {
           var mult = 0;
           // for each row:
           $("tr.field_group").each(function () {
               // get the values from this row:
               var $qty = $('.qty', this).text();
               var $unit_price_tax = $('.unit_price_tax', this).text();
               var $total = ($qty * 1) * ($unit_price_tax * 1)
               $('.total_unit_price_tax',this).text($total.toFixed(2));

               mult += $total;
            
           });
           $("#total_price_tax").text(mult.toFixed(2));
       }
$(".start").click(function() {

var final_status = '{{isset($entity_details->final_status)?$entity_details->final_status:''}}';
var created_by = '{{isset($purchase_order_master->created_by)?$purchase_order_master->created_by:''}}';
var vendor_name = '{{isset($purchase_order_master->vendor_name)?$purchase_order_master->vendor_name:''}}';
var vendor_email = '{{isset($purchase_order_master->vendor_email)?$purchase_order_master->vendor_email:''}}';
var transaction_id = this.id;
var entity_id =  '{{isset($entity_details->id)?$entity_details->id:''}}';
var entity_slug =  '{{isset($entity_details->entity_slug)?$entity_details->entity_slug:''}}';
var workflow_id ='{{isset($entity_details->workflow_id)?$entity_details->workflow_id:''}}';
var url = '{{url("/")}}/updateCurrentTransaction';
var id = '{{isset($purchase_order_master->id)?$purchase_order_master->id:''}}';
var status_name = $(this).val();
var eas_id = $('#eas').val();  
var privious_status = $(this).data('privious_status'); 
var subject =  '{{isset($purchase_order_master->subject)?$purchase_order_master->subject:''}}';
var sanction_title =  $('#eas :selected').text(); 
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
             if(status_name == "Return")  {
            comment(assignee=null);
            } 
             else if(status_name == 'Approve')  {
              updateTransaction(inputValue=null,assignee=null)
            }
            else {
              assignee();
            }
           // assignee();
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
      console.log('comment')
      swal({
                 title: "Please Enter Comment!",
                  text: "Why this PO Rejected ?",
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

        if(created_by && transaction_id && entity_id && workflow_id && url && vendor_name ) {

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
                    entity_id :entity_id,comment:inputValue,
                    workflow_id:workflow_id,assignee:assignee,old_assignee:old_assignee,
                    created_by:created_by,vendor_name:vendor_name,
                    entity_slug:entity_slug,eas_id:eas_id,sanction_title:sanction_title,
                    id:id,privious_status:privious_status,subject:subject,
                  status_name :status_name,final_status:final_status,vendor_email:vendor_email,
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
                   
                    if(data.status_id == final_status) {
                      swal({
                            title: "Approved",
                            text: data.message,
                            type: "success",
                            showConfirmButton: false,
                           timer: 1000
                        });
                       
                           window.location.href = "{{ url('/purchase-order') }}/"+id;
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
</script>     
@endsection 